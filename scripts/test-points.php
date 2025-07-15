<?php

/**
 * 點數累積功能測試腳本
 * 
 * 此腳本用於測試滿百元消費累積一點數的功能
 * 包含基本計算、生日當月雙倍、以及各種邊界情況
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;

class PointsCalculatorTest
{
    /**
     * 測試基本點數計算
     */
    public function testBasicPointsCalculation()
    {
        echo "=== 基本點數計算測試 ===\n";
        
        $testCases = [
            ['amount' => 80, 'expected' => 0, 'description' => '不足百元'],
            ['amount' => 100, 'expected' => 1, 'description' => '剛好百元'],
            ['amount' => 150, 'expected' => 1, 'description' => '超過百元'],
            ['amount' => 200, 'expected' => 2, 'description' => '整數倍'],
            ['amount' => 250, 'expected' => 2, 'description' => '非整數倍'],
            ['amount' => 500, 'expected' => 5, 'description' => '大額消費'],
            ['amount' => 999, 'expected' => 9, 'description' => '接近千元'],
            ['amount' => 1000, 'expected' => 10, 'description' => '整千元'],
        ];

        foreach ($testCases as $testCase) {
            $actual = $this->calculateBasePoints($testCase['amount']);
            $passed = $actual === $testCase['expected'];
            
            echo sprintf(
                "消費 %d 元 → 預期 %d 點，實際 %d 點 [%s]\n",
                $testCase['amount'],
                $testCase['expected'],
                $actual,
                $passed ? '✅ 通過' : '❌ 失敗'
            );
        }
        
        echo "\n";
    }

    /**
     * 測試生日當月雙倍點數
     */
    public function testBirthdayMonthDoublePoints()
    {
        echo "=== 生日當月雙倍點數測試 ===\n";
        
        $currentMonth = Carbon::now()->month;
        $birthdayInCurrentMonth = Carbon::create(null, $currentMonth, 15);
        $birthdayInOtherMonth = Carbon::create(null, ($currentMonth + 1) % 12, 15);
        
        $testCases = [
            [
                'amount' => 300,
                'birthday' => $birthdayInCurrentMonth,
                'expected' => 6,
                'description' => '生日當月消費'
            ],
            [
                'amount' => 300,
                'birthday' => $birthdayInOtherMonth,
                'expected' => 3,
                'description' => '非生日當月消費'
            ],
            [
                'amount' => 500,
                'birthday' => $birthdayInCurrentMonth,
                'expected' => 10,
                'description' => '生日當月大額消費'
            ],
            [
                'amount' => 80,
                'birthday' => $birthdayInCurrentMonth,
                'expected' => 0,
                'description' => '生日當月但不足百元'
            ],
        ];

        foreach ($testCases as $testCase) {
            $actual = $this->calculateFinalPoints($testCase['amount'], $testCase['birthday']);
            $passed = $actual === $testCase['expected'];
            
            echo sprintf(
                "%s：消費 %d 元 → 預期 %d 點，實際 %d 點 [%s]\n",
                $testCase['description'],
                $testCase['amount'],
                $testCase['expected'],
                $actual,
                $passed ? '✅ 通過' : '❌ 失敗'
            );
        }
        
        echo "\n";
    }

    /**
     * 測試邊界情況
     */
    public function testEdgeCases()
    {
        echo "=== 邊界情況測試 ===\n";
        
        $testCases = [
            ['amount' => 0, 'expected' => 0, 'description' => '零元消費'],
            ['amount' => -100, 'expected' => 0, 'description' => '負數消費'],
            ['amount' => 99, 'expected' => 0, 'description' => '99元消費'],
            ['amount' => 199, 'expected' => 1, 'description' => '199元消費'],
            ['amount' => 999999, 'expected' => 9999, 'description' => '極大金額'],
        ];

        foreach ($testCases as $testCase) {
            $actual = $this->calculateBasePoints($testCase['amount']);
            $passed = $actual === $testCase['expected'];
            
            echo sprintf(
                "%s：消費 %d 元 → 預期 %d 點，實際 %d 點 [%s]\n",
                $testCase['description'],
                $testCase['amount'],
                $testCase['expected'],
                $actual,
                $passed ? '✅ 通過' : '❌ 失敗'
            );
        }
        
        echo "\n";
    }

    /**
     * 測試實際業務場景
     */
    public function testBusinessScenarios()
    {
        echo "=== 業務場景測試 ===\n";
        
        $scenarios = [
            [
                'name' => '新會員首購',
                'amount' => 150,
                'birthday' => null,
                'expected' => 1,
                'description' => '新會員購買150元商品'
            ],
            [
                'name' => '生日當月購物',
                'amount' => 800,
                'birthday' => Carbon::create(null, Carbon::now()->month, 10),
                'expected' => 16,
                'description' => '生日當月購買800元商品'
            ],
            [
                'name' => '大額訂單',
                'amount' => 2500,
                'birthday' => null,
                'expected' => 25,
                'description' => '大額訂單2500元'
            ],
            [
                'name' => '小額累積',
                'amount' => 80,
                'birthday' => null,
                'expected' => 0,
                'description' => '小額消費不累積點數'
            ],
        ];

        foreach ($scenarios as $scenario) {
            $actual = $this->calculateFinalPoints($scenario['amount'], $scenario['birthday']);
            $passed = $actual === $scenario['expected'];
            
            echo sprintf(
                "%s：%s → 預期 %d 點，實際 %d 點 [%s]\n",
                $scenario['name'],
                $scenario['description'],
                $scenario['expected'],
                $actual,
                $passed ? '✅ 通過' : '❌ 失敗'
            );
        }
        
        echo "\n";
    }

    /**
     * 計算基本點數（滿百元消費累積一點）
     */
    private function calculateBasePoints($amount)
    {
        if ($amount <= 0) {
            return 0;
        }
        
        return intval($amount / 100);
    }

    /**
     * 檢查是否為生日當月
     */
    private function isBirthdayMonth($birthday)
    {
        if (!$birthday) {
            return false;
        }

        $currentMonth = Carbon::now()->month;
        $birthdayMonth = $birthday->month;

        return $birthdayMonth === $currentMonth;
    }

    /**
     * 計算最終點數（包含生日雙倍）
     */
    private function calculateFinalPoints($amount, $birthday = null)
    {
        $basePoints = $this->calculateBasePoints($amount);
        
        if ($this->isBirthdayMonth($birthday)) {
            $basePoints *= 2;
        }
        
        return $basePoints;
    }

    /**
     * 生成測試報告
     */
    public function generateReport()
    {
        echo "=== 點數累積功能測試報告 ===\n";
        echo "測試時間：" . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        echo "測試環境：PHP " . PHP_VERSION . "\n\n";
        
        $this->testBasicPointsCalculation();
        $this->testBirthdayMonthDoublePoints();
        $this->testEdgeCases();
        $this->testBusinessScenarios();
        
        echo "=== 測試完成 ===\n";
        echo "所有測試案例已執行完畢。\n";
        echo "如有失敗案例，請檢查計算邏輯。\n\n";
    }
}

// 執行測試
if (php_sapi_name() === 'cli') {
    $tester = new PointsCalculatorTest();
    $tester->generateReport();
} else {
    echo "此腳本需要在命令列中執行。\n";
    echo "使用方法：php scripts/test-points.php\n";
} 