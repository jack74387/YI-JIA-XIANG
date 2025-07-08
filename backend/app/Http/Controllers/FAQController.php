<?php
namespace App\Http\Controllers;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = [
            ['q' => '如何訂購商品？', 'a' => '可於網站直接下單，或聯絡客服協助訂購。'],
            ['q' => '有哪些付款方式？', 'a' => '支援信用卡、LINE Pay、銀行轉帳等多元付款。'],
            ['q' => '可以指定到貨日期嗎？', 'a' => '結帳時可選擇希望到貨日期，滿足節慶需求。'],
            ['q' => '商品有無添加防腐劑？', 'a' => '所有產品皆無添加防腐劑，請安心食用。'],
            ['q' => '如何查詢訂單進度？', 'a' => '登入會員後可於會員專區查詢訂單狀態。'],
        ];
        return response()->json(['success' => true, 'faqs' => $faqs]);
    }
} 