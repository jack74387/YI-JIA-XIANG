<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單狀態更新通知</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Microsoft JhengHei', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #d97706;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #d97706;
            margin: 0;
            font-size: 24px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin: 10px 0;
        }
        .status-processing { background-color: #e3f2fd; color: #1976d2; }
        .status-shipped { background-color: #fff3e0; color: #f57c00; }
        .status-delivered { background-color: #e8f5e8; color: #388e3c; }
        .status-cancelled { background-color: #ffebee; color: #d32f2f; }
        .order-items {
            margin: 20px 0;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-items th,
        .order-items td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-items th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>一佳香肉脯行</h1>
            <p>訂單狀態更新通知</p>
        </div>

        @php
            $statusMap = [
                'pending' => '待處理',
                'processing' => '處理中',
                'shipped' => '已出貨',
                'delivered' => '已送達',
                'cancelled' => '已取消'
            ];
            $statusText = $statusMap[$newStatus] ?? $newStatus;
            $statusClass = 'status-' . $newStatus;
        @endphp

        <div class="order-info">
            <h2>親愛的 {{ $order->user->name ?? '顧客' }} 您好：</h2>
            <p>您的訂單狀態已更新，詳細資訊如下：</p>
            
            <p><strong>訂單編號：</strong>#{{ $order->id }}</p>
            <p><strong>訂單狀態：</strong><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></p>
            <p><strong>訂單金額：</strong>NT$ {{ number_format($order->final_amount ?? $order->total) }}</p>
            <p><strong>收件人：</strong>{{ $order->recipient_name }}</p>
            <p><strong>收件電話：</strong>{{ $order->recipient_phone }}</p>
            
            @if($order->shipping_method)
            <p><strong>配送方式：</strong>{{ $order->shipping_method }}</p>
            @endif
            
            @if($order->shipping_method === '宅配' && $order->shipping_address)
            <p><strong>配送地址：</strong>{{ $order->shipping_address }}</p>
            @elseif($order->shipping_method === '門市自取' && $order->store_name)
            <p><strong>取貨門市：</strong>{{ $order->store_name }}</p>
            <p><strong>門市地址：</strong>{{ $order->store_address }}</p>
            @elseif($order->shipping_method === '超商取貨' && $order->convenience_store_name)
            <p><strong>取貨超商：</strong>{{ $order->convenience_store_name }} ({{ $order->convenience_store_chain }})</p>
            <p><strong>超商地址：</strong>{{ $order->convenience_store_address }}</p>
            @endif
        </div>

        @if($order->items && count($order->items) > 0)
        <div class="order-items">
            <h3>訂購商品明細：</h3>
            <table>
                <thead>
                    <tr>
                        <th>商品名稱</th>
                        <th>規格</th>
                        <th>數量</th>
                        <th>單價</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->spec_text ?? $item->spec ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>NT$ {{ number_format($item->price) }}</td>
                        <td>NT$ {{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($newStatus === 'processing')
        <div class="contact-info">
            <h4>處理中說明：</h4>
            <p>您的訂單正在準備中，我們會盡快為您出貨。如有任何問題，請隨時與我們聯繫。</p>
        </div>
        @elseif($newStatus === 'shipped')
        <div class="contact-info">
            <h4>出貨通知：</h4>
            <p>您的訂單已經出貨，預計1-3個工作天內送達。請保持電話暢通，以便配送人員聯繫。</p>
        </div>
        @elseif($newStatus === 'delivered')
        <div class="contact-info">
            <h4>感謝您的購買：</h4>
            <p>您的訂單已送達完成！感謝您選擇一佳香肉脯行，希望您滿意我們的商品。歡迎再次光臨！</p>
        </div>
        @elseif($newStatus === 'cancelled')
        <div class="contact-info">
            <h4>訂單取消說明：</h4>
            <p>很抱歉，您的訂單已被取消。如有疑問或需要協助，請聯繫我們的客服團隊。</p>
        </div>
        @endif

        <div class="footer">
            <h4>聯絡資訊</h4>
            <p>一佳香肉脯行</p>
            <p>客服電話：(089) 357-996</p>
            <p>客服信箱：yijiaxiang88@gmail.com</p>
            <p>營業時間：週一至週六 09:00-20:00</p>
            <br>
            <p style="font-size: 12px; color: #999;">此為系統自動發送的郵件，請勿直接回覆。</p>
        </div>
    </div>
</body>
</html>
