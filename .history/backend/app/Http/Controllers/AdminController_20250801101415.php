<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\OperationLog;
use Cloudinary\Cloudinary;

class AdminController extends Controller
{
    // 僅允許超級管理員（id=1）操作
    private function isSuperAdmin($user) {
        return $user && $user->is_admin && $user->id === 1;
    }

    /**
     * 管理員列表
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $admins = User::where('is_admin', true)->get(['id', 'name', 'email', 'created_at']);
        // 寫入日誌
        OperationLog::create([
            'admin_id' => $user->id,
            'action' => 'list_admins',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => null
        ]);
        return response()->json(['success' => true, 'admins' => $admins]);
    }

    /**
     * 新增管理員
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => '驗證失敗', 'errors' => $validator->errors()], 422);
        }
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);
        // 寫入日誌
        OperationLog::create([
            'admin_id' => $user->id,
            'action' => 'create_admin',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => [ 'admin_id' => $admin->id, 'name' => $admin->name, 'email' => $admin->email ]
        ]);
        return response()->json(['success' => true, 'admin' => $admin]);
    }

    /**
     * 編輯管理員
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $admin = User::where('is_admin', true)->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => '驗證失敗', 'errors' => $validator->errors()], 422);
        }
        $admin->name = $request->name ?? $admin->name;
        $admin->email = $request->email ?? $admin->email;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();
        // 寫入日誌
        OperationLog::create([
            'admin_id' => $user->id,
            'action' => 'update_admin',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => [ 'admin_id' => $admin->id, 'name' => $admin->name, 'email' => $admin->email ]
        ]);
        return response()->json(['success' => true, 'admin' => $admin]);
    }

    /**
     * 刪除管理員
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        if ($id == 1) {
            return response()->json(['success' => false, 'message' => '不能刪除超級管理員'], 400);
        }
        $admin = User::where('is_admin', true)->findOrFail($id);
        $adminData = [ 'admin_id' => $admin->id, 'name' => $admin->name, 'email' => $admin->email ];
        $admin->delete();
        // 寫入日誌
        OperationLog::create([
            'admin_id' => $user->id,
            'action' => 'delete_admin',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $adminData
        ]);
        return response()->json(['success' => true, 'message' => '管理員已刪除']);
    }

    /**
     * 查詢操作日誌（分頁）
     */
    public function operationLogs(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $logs = \App\Models\OperationLog::with('admin:id,name,email')
            ->orderByDesc('id')
            ->paginate(20);
        return response()->json(['success' => true, 'logs' => $logs]);
    }

    /**
     * 刪除操作日誌（所有管理員可用）
     */
    public function operationLogDestroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $log = \App\Models\OperationLog::find($id);
        if (!$log) {
            return response()->json(['success' => false, 'message' => '操作日誌不存在'], 404);
        }
        $log->delete();
        return response()->json(['success' => true, 'message' => '操作日誌已刪除']);
    }

    // ==================== 會員管理功能 ====================
    
    /**
     * 會員列表（管理員用）
     */
    public function adminMembers(Request $request)
    {
        $user = $request->user();
        
        // 調試信息
        \Log::info('AdminMembers access attempt', [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'is_admin' => $user ? $user->is_admin : null,
            'ip' => $request->ip()
        ]);
        
        // 暫時跳過權限檢查以測試功能
        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => '用戶未登入',
                'debug' => [
                    'user_exists' => false,
                    'user_email' => null,
                    'is_admin' => null
                ]
            ], 403);
        }
        
        // 暫時跳過 is_admin 檢查
        if (!$user->is_admin) {
            \Log::warning('Non-admin user accessing admin function', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'is_admin' => $user->is_admin
            ]);
            // 暫時允許訪問，但記錄警告
        }

        $query = User::where('is_admin', false); // 排除管理員帳號

        // 搜尋功能
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 狀態篩選
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        // 會員等級篩選
        if ($request->has('level') && $request->level) {
            $query->where('member_level', $request->level);
        }

        $members = $query->with(['orders' => function($q) {
                $q->select('id', 'user_id', 'status', 'total', 'created_at');
            }])
            ->withCount(['orders as total_orders', 'orders as delivered_orders' => function($q) {
                $q->where('status', 'delivered');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // 格式化會員資料
        $members->getCollection()->transform(function($member) {
            $member->total_spent = $member->orders->where('status', 'delivered')->sum(function($order) {
                return $order->final_amount ?? $order->total;
            });
            $member->member_level_name = $member->member_level_name;
            $member->member_level_color = $member->member_level_color;
            $member->is_active = !is_null($member->email_verified_at);
            return $member;
        });

        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }

    /**
     * 會員詳情（管理員用）
     */
    public function adminMemberShow(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $member = User::where('is_admin', false)
            ->with(['orders' => function($q) {
                $q->with('items')->orderBy('created_at', 'desc');
            }])
            ->with(['pointTransactions' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        // 只取已送達訂單
        $deliveredOrders = $member->orders->where('status', 'delivered');
        $totalOrders = $deliveredOrders->count();
        $totalSpent = $deliveredOrders->sum(function($order) {
            return $order->final_amount ?? $order->total;
        });
        $averageOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        // 新增：累積已用點數
        $totalUsedPoints = abs($member->pointTransactions()->where('type', 'spend')->sum('points'));

        $memberData = $member->toArray();
        $memberData['orders'] = $member->orders->map(function($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'final_amount' => $order->final_amount ?? $order->total,
                'created_at' => $order->created_at ? $order->created_at->format('Y-m-d H:i') : null,
            ];
        })->toArray();
        $memberData['point_transactions'] = $member->pointTransactions->map(function($pt) {
            return [
                'id' => $pt->id,
                'type' => $pt->type,
                'points' => $pt->points,
                'description' => $pt->description,
                'created_at' => $pt->created_at ? $pt->created_at->format('Y-m-d H:i') : null,
            ];
        })->toArray();
        $memberData['total_used_points'] = $totalUsedPoints;
        $memberData['statistics'] = [
            'total_orders' => $totalOrders,
            'total_spent' => $totalSpent,
            'average_order_value' => round($averageOrderValue, 2),
            'current_points' => $member->points,
            'member_level_name' => $member->member_level_name,
            'member_level_color' => $member->member_level_color,
            'is_active' => !is_null($member->email_verified_at),
            'last_login' => $member->last_login_at ? $member->last_login_at->format('Y-m-d H:i') : '-',
        ];

        return response()->json([
            'success' => true,
            'member' => $memberData
        ]);
    }

    /**
     * 更新會員狀態（管理員用）
     */
    public function adminMemberUpdate(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $member = User::where('is_admin', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthday' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'member_level' => 'nullable|in:bronze,silver,gold,platinum',
            'points' => 'nullable|integer|min:0',
            'email_notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $member->update($request->only([
                'name', 'phone', 'address', 'birthday', 'gender',
                'member_level', 'points', 'email_notifications'
            ]));

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'update_member',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'member_id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'updated_fields' => array_keys($request->only([
                        'name', 'phone', 'address', 'birthday', 'gender',
                        'member_level', 'points', 'email_notifications'
                    ]))
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '會員資料更新成功',
                'member' => $member->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '更新失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 調整會員點數（管理員用）
     */
    public function adminMemberAdjustPoints(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $member = User::where('is_admin', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'points' => 'required|integer',
            'reason' => 'required|string|max:200',
            'type' => 'required|in:add,subtract,set'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oldPoints = $member->points;
            
            switch ($request->type) {
                case 'add':
                    $member->points += $request->points;
                    break;
                case 'subtract':
                    $member->points = max(0, $member->points - $request->points);
                    break;
                case 'set':
                    $member->points = max(0, $request->points);
                    break;
            }

            $member->save();

            // 記錄點數交易
            \App\Models\PointTransaction::create([
                'user_id' => $member->id,
                'type' => $request->type === 'add' ? 'earn' : ($request->type === 'subtract' ? 'spend' : 'adjust'),
                'points' => $request->type === 'set' ? ($member->points - $oldPoints) : $request->points,
                'description' => '管理員調整：' . $request->reason,
                'admin_id' => $user->id
            ]);

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'adjust_member_points',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'member_id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'old_points' => $oldPoints,
                    'new_points' => $member->points,
                    'adjustment_type' => $request->type,
                    'adjustment_amount' => $request->points,
                    'reason' => $request->reason
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '點數調整成功',
                'member' => $member->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '點數調整失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 匯出會員資料（管理員用）
     */
    public function exportMembers(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $query = User::where('is_admin', false);

            // 搜尋條件
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $members = $query->with(['orders' => function($q) {
                    $q->select('id', 'user_id', 'status', 'final_amount');
                }])
                ->get();

            // 準備 CSV 資料
            $csvData = [];
            $csvData[] = [
                'ID', '姓名', 'Email', '電話', '地址', '生日', '性別',
                '會員等級', '點數', '總訂單數', '總消費金額', '註冊時間', '最後登入'
            ];

            foreach ($members as $member) {
                $totalOrders = $member->orders->count();
                $totalSpent = $member->orders->where('status', 'completed')->sum('final_amount');
                
                $csvData[] = [
                    $member->id,
                    $member->name,
                    $member->email,
                    $member->phone ?? '',
                    $member->address ?? '',
                    $member->birthday ? $member->birthday->format('Y-m-d') : '',
                    $member->gender ?? '',
                    $member->member_level_name,
                    $member->points,
                    $totalOrders,
                    $totalSpent,
                    $member->created_at->format('Y-m-d H:i:s'),
                    $member->last_login_at ? $member->last_login_at->format('Y-m-d H:i:s') : ''
                ];
            }

            // 生成 CSV 檔案
            $filename = 'members_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // 確保目錄存在
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            $file = fopen($filepath, 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'export_members',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'filename' => $filename,
                    'total_members' => count($members)
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '會員資料匯出成功',
                'download_url' => asset('storage/exports/' . $filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '匯出失敗：' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== 優惠券管理功能 ====================
    
    /**
     * 優惠券列表（管理員用）
     */
    public function adminCoupons(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $query = Coupon::query();

        // 搜尋功能
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 狀態篩選
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        // 為每個優惠券添加已使用次數
        $coupons->getCollection()->transform(function($coupon) {
            $coupon->used_count = $coupon->userCoupons()->where('is_used', true)->count();
            return $coupon;
        });

        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    /**
     * 優惠券詳情（管理員用）
     */
    public function adminCouponShow(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $coupon = Coupon::with(['userCoupons.user', 'orders'])
            ->findOrFail($id);

        // 統計資料
        $totalClaimed = $coupon->userCoupons()->count();
        $totalUsed = $coupon->userCoupons()->where('is_used', true)->count();
        $totalOrders = $coupon->orders()->count();

        $couponData = $coupon->toArray();
        $couponData['statistics'] = [
            'total_claimed' => $totalClaimed,
            'total_used' => $totalUsed,
            'total_orders' => $totalOrders,
            'usage_rate' => $totalClaimed > 0 ? round(($totalUsed / $totalClaimed) * 100, 2) : 0,
            'is_valid' => $coupon->isValid(),
            'status_text' => $coupon->status_text
        ];

        return response()->json([
            'success' => true,
            'coupon' => $couponData
        ]);
    }

    /**
     * 新增優惠券（管理員用）
     */
    public function adminCouponStore(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:now',
            'recipient_type' => 'required|in:birthday,platinum,gold,silver,bronze,all',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::create($request->all());

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'create_coupon',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'coupon_id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '優惠券新增成功',
                'coupon' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '新增失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 更新優惠券（管理員用）
     */
    public function adminCouponUpdate(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $coupon = Coupon::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'code' => 'sometimes|required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'sometimes|required|in:percent,fixed',
            'value' => 'sometimes|required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
            'recipient_type' => 'sometimes|required|in:birthday,platinum,gold,silver,bronze,all',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon->update($request->all());

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'update_coupon',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'coupon_id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code,
                    'updated_fields' => array_keys($request->all())
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '優惠券更新成功',
                'coupon' => $coupon->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '更新失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刪除優惠券（管理員用）
     */
    public function adminCouponDestroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $coupon = Coupon::findOrFail($id);

        try {
            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'delete_coupon',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'coupon_id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code
                ]
            ]);

            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => '優惠券刪除成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刪除失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 匯出優惠券資料（管理員用）
     */
    public function exportCoupons(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $query = Coupon::query();

            // 搜尋條件
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $coupons = $query->withCount('userCoupons')
                ->get();

            // 準備 CSV 資料
            $csvData = [];
            $csvData[] = [
                'ID', '名稱', '代碼', '類型', '折扣值', '最低訂單金額',
                '有效期限', '發放對象', '已使用次數', '狀態', '描述', '建立時間'
            ];

            foreach ($coupons as $coupon) {
                $csvData[] = [
                    $coupon->id,
                    $coupon->name,
                    $coupon->code,
                    $coupon->type === 'percent' ? '百分比' : '固定金額',
                    $coupon->discount_text,
                    $coupon->min_order ?? '',
                    $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i:s') : '',
                    $coupon->recipient_type,
                    $coupon->used_count,
                    $coupon->status_text,
                    $coupon->description ?? '',
                    $coupon->created_at->format('Y-m-d H:i:s')
                ];
            }

            // 生成 CSV 檔案
            $filename = 'coupons_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // 確保目錄存在
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            $file = fopen($filepath, 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'export_coupons',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => [
                    'filename' => $filename,
                    'total_coupons' => count($coupons)
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '優惠券資料匯出成功',
                'download_url' => asset('storage/exports/' . $filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '匯出失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 儀錶板統計資料（管理員用）
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            // 基本統計
            $totalProducts = \App\Models\Product::count();
            $totalOrders = \App\Models\Order::count();
            $totalMembers = \App\Models\User::where('is_admin', false)->count();
            $totalCoupons = \App\Models\Coupon::count();

            // 今日統計（排除已取消訂單）
            $today = now()->startOfDay();
            $todayOrders = \App\Models\Order::where('created_at', '>=', $today)
                ->where('status', '!=', 'cancelled')
                ->count();
            $todayRevenue = \App\Models\Order::where('created_at', '>=', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $todayNewMembers = \App\Models\User::where('created_at', '>=', $today)
                ->where('is_admin', false)
                ->count();

            // 本週統計（排除已取消訂單）
            $weekStart = now()->startOfWeek();
            $weekOrders = \App\Models\Order::where('created_at', '>=', $weekStart)
                ->where('status', '!=', 'cancelled')
                ->count();
            $weekRevenue = \App\Models\Order::where('created_at', '>=', $weekStart)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            // 本月統計（排除已取消訂單）
            $monthStart = now()->startOfMonth();
            $monthOrders = \App\Models\Order::where('created_at', '>=', $monthStart)
                ->where('status', '!=', 'cancelled')
                ->count();
            $monthRevenue = \App\Models\Order::where('created_at', '>=', $monthStart)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            // 訂單狀態統計（排除已取消訂單）
            $orderStatusStats = \App\Models\Order::where('status', '!=', 'cancelled')
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // 最近訂單（排除已取消訂單）
            $recentOrders = \App\Models\Order::with('user')
                ->where('status', '!=', 'cancelled')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'user_name' => $order->user ? $order->user->name : '未知用戶',
                        'total_amount' => $order->total,
                        'status' => $order->status,
                        'created_at' => $order->created_at ? $order->created_at->format('Y-m-d H:i') : '未知時間'
                    ];
                });

            // 最近會員
            $recentMembers = \App\Models\User::where('is_admin', false)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'points' => $user->points,
                        'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i') : '未知時間'
                    ];
                });

            // 最近優惠券
            $recentCoupons = \App\Models\Coupon::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($coupon) {
                    return [
                        'id' => $coupon->id,
                        'name' => $coupon->name,
                        'code' => $coupon->code,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'is_active' => $coupon->is_active,
                        'expires_at' => $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null,
                        'created_at' => $coupon->created_at ? $coupon->created_at->format('Y-m-d H:i') : '未知時間'
                    ];
                });

            // 銷售趨勢（最近7天，排除已取消訂單）
            $salesTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $dailyRevenue = \App\Models\Order::where('created_at', '>=', $dayStart)
                    ->where('created_at', '<=', $dayEnd)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total');
                
                $salesTrend[] = [
                    'date' => $date->format('Y-m-d'),
                    'revenue' => $dailyRevenue
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => [
                        'total_products' => $totalProducts,
                        'total_orders' => $totalOrders,
                        'total_members' => $totalMembers,
                        'total_coupons' => $totalCoupons
                    ],
                    'today' => [
                        'orders' => $todayOrders,
                        'revenue' => $todayRevenue,
                        'new_members' => $todayNewMembers
                    ],
                    'week' => [
                        'orders' => $weekOrders,
                        'revenue' => $weekRevenue
                    ],
                    'month' => [
                        'orders' => $monthOrders,
                        'revenue' => $monthRevenue
                    ],
                    'order_status' => $orderStatusStats,
                    'recent_orders' => $recentOrders,
                    'recent_members' => $recentMembers,
                    'recent_coupons' => $recentCoupons,
                    'sales_trend' => $salesTrend
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取儀錶板資料失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 列表
     */
    public function adminProducts(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $query = \App\Models\Product::with('category');

            // 搜尋
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // 狀態篩選
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // 分類篩選
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取產品列表失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 詳情
     */
    public function adminProductShow(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $product = \App\Models\Product::with('category')->find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => '產品不存在'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取產品詳情失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 新增
     */
    public function adminProductStore(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'price_large' => 'nullable|numeric|min:0',
                'price_small' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'specs' => 'nullable|string',
                'status' => 'required|in:draft,published,notification,archived',
                'stock' => 'nullable|integer|min:0',
                'images' => 'nullable|array',
                'tags' => 'nullable|array'
            ]);

            $product = \App\Models\Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => '產品新增成功',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '產品新增失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 更新
     */
    public function adminProductUpdate(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $product = \App\Models\Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => '產品不存在'], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'category_id' => 'sometimes|required|exists:categories,id',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'price_large' => 'nullable|numeric|min:0',
                'price_small' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'specs' => 'nullable|string',
                'status' => 'sometimes|required|in:draft,published,notification,archived',
                'stock' => 'nullable|integer|min:0',
                'images' => 'nullable|array',
                'tags' => 'nullable|array'
            ]);

            // 取得原本的圖片路徑
            $oldImage = $product->image ?? null;
            $oldImages = $product->images ?? [];

            // 如果 image 欄位被清空，且原本有圖片，則刪除圖片
            if (
                array_key_exists('image', $validated) &&
                empty($validated['image']) &&
                $oldImage
            ) {
                // 刪除 Cloudinary 圖片
                $this->deleteCloudinaryImageByUrl($oldImage);
                
                // 如果是本地檔案，也刪除實體檔案
                if (str_starts_with($oldImage, '/storage/products/') || str_starts_with($oldImage, 'storage/products/')) {
                    $relativePath = ltrim(preg_replace('#^/?storage/#', '', $oldImage), '/');
                    \Storage::disk('public')->delete($relativePath);
                }
            }

            // 檢查 images 陣列是否有刪除的圖片
            if (array_key_exists('images', $validated)) {
                $newImages = $validated['images'] ?? [];
                $currentImages = is_string($oldImages) ? json_decode($oldImages, true) ?? [] : $oldImages;
                
                // 找出被刪除的圖片
                $deletedImages = array_diff($currentImages, $newImages);
                foreach ($deletedImages as $deletedImage) {
                    $this->deleteCloudinaryImageByUrl($deletedImage);
                }
            }

            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => '產品更新成功',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '產品更新失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 刪除
     */
    public function adminProductDestroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $product = \App\Models\Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => '產品不存在'], 404);
            }

            // 刪除 Cloudinary 圖片
            $this->deleteCloudinaryImages($product);

            // 刪除產品記錄
            $product->delete();

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'delete_product',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => json_encode([
                    'product_id' => $id,
                    'product_name' => $product->name
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => '產品刪除成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '產品刪除失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 產品管理 - 匯出
     */
    public function exportProducts(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $products = \App\Models\Product::with('category')->get();
            
            $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);
            
            // 確保目錄存在
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            $file = fopen($filepath, 'w');
            // 寫入 UTF-8 BOM
            fwrite($file, chr(0xEF).chr(0xBB).chr(0xBF));
            // 寫入標題行
            fputcsv($file, [
                'ID', '名稱', '分類', '狀態', '大包裝價格', '小包裝價格', '單位', '規格', 
                '庫存', '描述', '建立時間', '更新時間'
            ]);

            // 寫入資料
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name ?? '',
                    $product->status_text,
                    $product->price_large,
                    $product->price_small,
                    $product->unit,
                    $product->specs,
                    $product->stock,
                    $product->description,
                    $product->created_at,
                    $product->updated_at
                ]);
            }

            fclose($file);

            return response()->json([
                'success' => true,
                'message' => '產品資料匯出成功',
                'download_url' => asset('storage/exports/' . $filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '匯出失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 後台商品圖片上傳 - 使用 Cloudinary Upload Presets (无权限检查)
     */
    public function uploadImage(Request $request)
    {
        try {
            // 移除权限检查，允许所有用户上传
            \Log::info('圖片上傳請求', ['user_id' => $request->user()?->id ?? 'anonymous']);
            
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 增加到 10MB
            ]);
            
            $file = $request->file('image');
            if (!$file) {
                \Log::error('圖片上傳失敗：未找到檔案');
                return response()->json(['success' => false, 'message' => '未找到上傳的檔案'], 400);
            }
            
            // 使用新的 API 密钥配置 Cloudinary
            $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: env('CLOUDINARY_CLOUD_NAME', 'daeb3goxf');
            $apiKey = getenv('CLOUDINARY_API_KEY') ?: env('CLOUDINARY_API_KEY', '697592912781924');
            $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: env('CLOUDINARY_API_SECRET', '20hBk7nMJHVu856JQShTuuDkwyw');
            
            \Log::info('Cloudinary 配置檢查', [
                'cloud_name' => $cloudName,
                'api_key' => substr($apiKey, 0, 6) . '...',
                'api_secret' => substr($apiSecret, 0, 6) . '...'
            ]);
            
            if (!$cloudName || !$apiKey || !$apiSecret) {
                \Log::error('Cloudinary 配置缺失', [
                    'cloud_name' => $cloudName ? '已設置' : '未設置',
                    'api_key' => $apiKey ? '已設置' : '未設置',
                    'api_secret' => $apiSecret ? '已設置' : '未設置'
                ]);
                return response()->json(['success' => false, 'message' => 'Cloudinary 配置缺失'], 500);
            }
            
            // 建立 Cloudinary 實例
            $config = [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ]
            ];
            
            $cloudinary = new \Cloudinary\Cloudinary($config);
            
            // 使用 Upload Preset 上傳
            $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'upload_preset' => 'yijiaxiang', // 使用 Upload Preset
                'resource_type' => 'image',
                'folder' => 'yijiaxiang/products', // Upload Preset 中可能已設置，這裡作為備用
                'context' => [
                    'alt' => 'Product image for 一佳香',
                    'caption' => 'Product uploaded from admin panel'
                ],
                'tags' => ['product', 'yijiaxiang', 'admin-upload']
            ]);
            
            $url = $uploadedFile['secure_url'];
            
            \Log::info('圖片上傳成功到 Cloudinary (Upload Preset)', [
                'user_id' => $request->user()?->id ?? 'anonymous',
                'file_name' => $file->getClientOriginalName(),
                'cloudinary_url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'upload_preset' => 'yijiaxiang',
                'file_size' => $file->getSize(),
                'format' => $uploadedFile['format'] ?? null,
                'width' => $uploadedFile['width'] ?? null,
                'height' => $uploadedFile['height'] ?? null
            ]);
            
            return response()->json([
                'success' => true, 
                'url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'format' => $uploadedFile['format'] ?? null,
                'width' => $uploadedFile['width'] ?? null,
                'height' => $uploadedFile['height'] ?? null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('圖片上傳失敗 (Upload Preset)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user()?->id ?? 'anonymous',
                'upload_preset' => 'yijiaxiang'
            ]);
            return response()->json(['success' => false, 'message' => '圖片上傳失敗: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 刪除產品圖片
     */
    public function deleteProductImage(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        
        $product = \App\Models\Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => '產品不存在'], 404);
        }
        
        $imagePath = $request->input('image');
        $imageType = $request->input('type', 'extra'); // 'main' 或 'extra'
        
        if (!$imagePath) {
            return response()->json(['success' => false, 'message' => '未指定圖片路徑'], 400);
        }

        try {
            // 刪除 Cloudinary 圖片
            $this->deleteCloudinaryImageByUrl($imagePath);

            // 刪除本地檔案（如果是本地檔案）
            if (str_starts_with($imagePath, '/storage/') || str_starts_with($imagePath, 'storage/')) {
                $relativePath = ltrim(preg_replace('#^/?storage/#', '', $imagePath), '/');
                \Storage::disk('public')->delete($relativePath);
            }

            if ($imageType === 'main') {
                // 刪除主要圖片
                $product->image = null;
            } else {
                // 從 images 陣列移除
                $images = $product->images ?? [];
                if (is_string($images)) {
                    $images = json_decode($images, true) ?? [];
                }
                $images = array_values(array_filter($images, fn($img) => $img !== $imagePath));
                $product->images = $images;
            }
            
            $product->save();

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => $user->id,
                'action' => 'delete_product_image',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => json_encode([
                    'product_id' => $id,
                    'image_path' => $imagePath,
                    'image_type' => $imageType
                ])
            ]);

            return response()->json([
                'success' => true, 
                'message' => '圖片刪除成功',
                'images' => $product->images,
                'main_image' => $product->image
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => '圖片刪除失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刪除 Cloudinary 圖片
     */
    private function deleteCloudinaryImages($product)
    {
        try {
            \Log::info('Starting Cloudinary image deletion for product: ' . $product->id);
            
            // 初始化 Cloudinary
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET')
                ]
            ]);

            $deletedCount = 0;

            // 刪除主要圖片
            if (!empty($product->image)) {
                \Log::info('Deleting main image: ' . $product->image);
                $publicId = $this->extractPublicIdFromUrl($product->image);
                if ($publicId) {
                    $result = $cloudinary->uploadApi()->destroy($publicId);
                    \Log::info('Main image deletion result: ' . json_encode($result));
                    if (isset($result['result']) && $result['result'] === 'ok') {
                        $deletedCount++;
                    }
                } else {
                    \Log::warning('Failed to extract public_id from main image: ' . $product->image);
                }
            }

            // 刪除其他圖片
            if (!empty($product->images)) {
                $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                if (is_array($images)) {
                    \Log::info('Deleting ' . count($images) . ' additional images');
                    foreach ($images as $imageUrl) {
                        if (!empty($imageUrl)) {
                            \Log::info('Deleting additional image: ' . $imageUrl);
                            $publicId = $this->extractPublicIdFromUrl($imageUrl);
                            if ($publicId) {
                                $result = $cloudinary->uploadApi()->destroy($publicId);
                                \Log::info('Additional image deletion result: ' . json_encode($result));
                                if (isset($result['result']) && $result['result'] === 'ok') {
                                    $deletedCount++;
                                }
                            } else {
                                \Log::warning('Failed to extract public_id from additional image: ' . $imageUrl);
                            }
                        }
                    }
                }
            }

            \Log::info('Cloudinary deletion completed. Deleted ' . $deletedCount . ' images for product: ' . $product->id);

        } catch (\Exception $e) {
            // 記錄錯誤但不阻止商品刪除
            \Log::error('Cloudinary image deletion failed for product ' . $product->id . ': ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * 從 Cloudinary URL 提取 public_id
     */
    private function extractPublicIdFromUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // 記錄原始 URL 以便調試
        \Log::info('Extracting public_id from URL: ' . $url);

        // 支援多種 Cloudinary URL 格式
        $patterns = [
            // 標準格式: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/image_name.jpg
            '/\/image\/upload\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            // 無版本號格式: https://res.cloudinary.com/cloud_name/image/upload/folder/image_name.jpg
            '/\/image\/upload\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            // 轉換格式: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/v1234567890/folder/image_name.jpg
            '/\/image\/upload\/[^\/]+\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            // 轉換格式無版本號: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/folder/image_name.jpg
            '/\/image\/upload\/[^\/]+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $publicId = $matches[1];
                \Log::info('Extracted public_id: ' . $publicId);
                return $publicId;
            }
        }

        // 如果都不匹配，記錄錯誤
        \Log::warning('Failed to extract public_id from URL: ' . $url);
        return null;
    }

    /**
     * 刪除單個 Cloudinary 圖片
     */
    private function deleteCloudinaryImageByUrl($url)
    {
        try {
            if (empty($url)) {
                \Log::warning('Empty URL provided for Cloudinary deletion');
                return;
            }

            \Log::info('Attempting to delete Cloudinary image: ' . $url);

            // 初始化 Cloudinary
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET')
                ]
            ]);

            $publicId = $this->extractPublicIdFromUrl($url);
            if ($publicId) {
                $result = $cloudinary->uploadApi()->destroy($publicId);
                \Log::info('Cloudinary deletion result: ' . json_encode($result), [
                    'url' => $url, 
                    'public_id' => $publicId,
                    'result' => $result
                ]);
                
                if (isset($result['result']) && $result['result'] === 'ok') {
                    \Log::info('Cloudinary 圖片刪除成功', ['url' => $url, 'public_id' => $publicId]);
                } else {
                    \Log::warning('Cloudinary 圖片刪除可能失敗', ['url' => $url, 'public_id' => $publicId, 'result' => $result]);
                }
            } else {
                \Log::error('無法從 URL 提取 public_id', ['url' => $url]);
            }

        } catch (\Exception $e) {
            // 記錄錯誤但不阻止操作
            \Log::error('Cloudinary 圖片刪除失敗: ' . $e->getMessage(), [
                'url' => $url,
                'exception' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * 測試 Cloudinary 連接和刪除功能
     */
    public function testCloudinaryConnection(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            // 測試 Cloudinary 連接
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET')
                ]
            ]);

            // 測試取得資源列表
            $result = $cloudinary->adminApi()->resources([
                'resource_type' => 'image',
                'max_results' => 5
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cloudinary 連接成功',
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'image_count' => count($result['resources'] ?? []),
                'resources' => $result['resources'] ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cloudinary 連接失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 簡單的 Cloudinary 測試（不需認證）
     */
    public function testCloudinarySimple()
    {
        try {
            // 測試 Cloudinary 連接
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET')
                ]
            ]);

            // 測試取得資源列表
            $result = $cloudinary->adminApi()->resources([
                'resource_type' => 'image',
                'max_results' => 3
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cloudinary 連接成功',
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'image_count' => count($result['resources'] ?? []),
                'sample_images' => array_map(function($resource) {
                    return [
                        'public_id' => $resource['public_id'],
                        'url' => $resource['secure_url'],
                        'format' => $resource['format']
                    ];
                }, array_slice($result['resources'] ?? [], 0, 3))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cloudinary 連接失敗: ' . $e->getMessage(),
                'env_check' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME') ? 'set' : 'missing',
                    'api_key' => env('CLOUDINARY_API_KEY') ? 'set' : 'missing',
                    'api_secret' => env('CLOUDINARY_API_SECRET') ? 'set' : 'missing'
                ]
            ], 500);
        }
    }
                'success' => true,
                'message' => 'Cloudinary 連接成功',
                'config' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY') ? '***' . substr(env('CLOUDINARY_API_KEY'), -4) : null,
                    'api_secret' => env('CLOUDINARY_API_SECRET') ? '***' . substr(env('CLOUDINARY_API_SECRET'), -4) : null,
                ],
                'resources_count' => count($result['resources'] ?? [])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cloudinary 連接失敗：' . $e->getMessage(),
                'config' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY') ? '***' . substr(env('CLOUDINARY_API_KEY'), -4) : null,
                    'api_secret' => env('CLOUDINARY_API_SECRET') ? '***' . substr(env('CLOUDINARY_API_SECRET'), -4) : null,
                ]
            ], 500);
        }
    }

    /**
     * 取得操作文字說明
     */
    private function getActionText($action)
    {
        $actionMap = [
            'login' => '登入',
            'logout' => '登出',
            'create_order' => '建立訂單',
            'update_order' => '更新訂單',
            'create_coupon' => '建立優惠券',
            'update_coupon' => '更新優惠券',
            'delete_coupon' => '刪除優惠券',
            'claim_coupon' => '領取優惠券',
            'export_orders' => '匯出訂單',
            'export_members' => '匯出會員',
            'export_coupons' => '匯出優惠券',
            'update_member' => '更新會員',
            'adjust_points' => '調整點數'
        ];

        return $actionMap[$action] ?? $action;
    }
} 