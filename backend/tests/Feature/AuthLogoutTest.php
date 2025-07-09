<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout_successfully()
    {
        // 建立測試用戶
        $user = User::factory()->create();
        
        // 模擬已認證的用戶
        Sanctum::actingAs($user);
        
        // 發送登出請求
        $response = $this->postJson('/api/v1/auth/logout');
        
        // 驗證回應
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => '登出成功'
                ]);
    }

    public function test_unauthenticated_user_cannot_logout()
    {
        // 發送登出請求（未認證）
        $response = $this->postJson('/api/v1/auth/logout');
        
        // 驗證回應
        $response->assertStatus(401);
    }

    public function test_logout_removes_user_token()
    {
        // 建立測試用戶
        $user = User::factory()->create();
        
        // 模擬已認證的用戶並建立 token
        $token = $user->createToken('test-token')->plainTextToken;
        
        // 使用 token 發送登出請求
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/auth/logout');
        
        // 驗證回應
        $response->assertStatus(200);
        
        // 驗證用戶無法再使用同一個 token 存取受保護的資源
        $userResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/auth/user');
        
        $userResponse->assertStatus(401);
    }
} 