<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PointTransaction;
use App\Models\UserCoupon;
use App\Models\OperationLog;
use Illuminate\Support\Facades\DB;

class ClearMemberData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:clear {--force : 強制執行，跳過確認}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除所有會員相關資料（保留管理員帳號）';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== 會員資料清除工具 ===');
        $this->newLine();

        // 顯示清除前的統計
        $this->showStatistics('清除前的統計');

        // 確認是否繼續
        if (!$this->option('force')) {
            if (!$this->confirm('警告：此操作將清除所有會員相關資料！是否繼續？')) {
                $this->info('操作已取消。');
                return;
            }
        }

        $this->newLine();
        $this->info('開始清除會員資料...');

        try {
            // 開始事務
            DB::beginTransaction();

            // 1. 清除用戶優惠券
            $userCouponCount = UserCoupon::count();
            UserCoupon::truncate();
            $this->info("✓ 已清除 {$userCouponCount} 個用戶優惠券記錄");

            // 2. 清除點數交易記錄
            $pointTransactionCount = PointTransaction::count();
            PointTransaction::truncate();
            $this->info("✓ 已清除 {$pointTransactionCount} 個點數交易記錄");

            // 3. 清除購物車項目
            $cartItemCount = CartItem::count();
            CartItem::truncate();
            $this->info("✓ 已清除 {$cartItemCount} 個購物車項目");

            // 4. 清除購物車
            $cartCount = Cart::count();
            Cart::truncate();
            $this->info("✓ 已清除 {$cartCount} 個購物車");

            // 5. 清除訂單
            $orderCount = Order::count();
            Order::truncate();
            $this->info("✓ 已清除 {$orderCount} 個訂單");

            // 6. 清除會員用戶（保留管理員）
            $memberCount = User::where('is_admin', false)->count();
            User::where('is_admin', false)->delete();
            $this->info("✓ 已清除 {$memberCount} 個會員帳號");

            // 7. 清除操作日誌
            $operationLogCount = OperationLog::count();
            OperationLog::truncate();
            $this->info("✓ 已清除 {$operationLogCount} 個操作日誌");

            // 提交事務
            DB::commit();

            $this->newLine();
            $this->info('=== 清除完成 ===');
            $this->showStatistics('清除後的統計');

            $this->info('✓ 所有會員資料已成功清除！');

        } catch (\Exception $e) {
            // 回滾事務
            DB::rollback();
            $this->error("❌ 清除過程中發生錯誤：" . $e->getMessage());
            $this->info('所有變更已回滾。');
        }
    }

    /**
     * 顯示統計資訊
     */
    private function showStatistics($title)
    {
        $this->info($title . '：');
        $this->line("總用戶數: " . User::count());
        $this->line("管理員數: " . User::where('is_admin', true)->count());
        $this->line("會員數: " . User::where('is_admin', false)->count());
        $this->line("訂單數: " . Order::count());
        $this->line("購物車數: " . Cart::count());
        $this->line("購物車項目數: " . CartItem::count());
        $this->line("點數交易數: " . PointTransaction::count());
        $this->line("用戶優惠券數: " . UserCoupon::count());
        $this->line("操作日誌數: " . OperationLog::count());
        $this->newLine();
    }
}
