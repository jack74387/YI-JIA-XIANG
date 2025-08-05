<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * 處理聯絡表單提交
     */
    public function submit(Request $request)
    {
        try {
            // 驗證表單數據
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:2000',
                'to_email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => '表單驗證失敗',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // 記錄日誌
            Log::info('Contact form submission', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => substr($validated['message'], 0, 100) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // 發送郵件
            $this->sendContactEmail($validated);

            return response()->json([
                'success' => true,
                'message' => '訊息已成功送出，我們會盡快回覆您！'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '系統錯誤，請稍後再試或直接致電聯繫'
            ], 500);
        }
    }

    /**
     * 發送聯絡郵件
     */
    private function sendContactEmail($data)
    {
        try {
            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->to($data['to_email'])
                       ->subject('【一佳香肉脯行】網站聯絡表單 - ' . $data['name'])
                       ->replyTo($data['email'], $data['name']);
            });

            // 發送確認郵件給客戶
            Mail::send('emails.contact-confirmation', $data, function ($message) use ($data) {
                $message->to($data['email'])
                       ->subject('【一佳香肉脯行】感謝您的聯絡');
            });

        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }
}
