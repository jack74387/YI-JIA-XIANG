<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聯絡表單 - {{ $name }}</title>
    <style>
        body {
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f59e0b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e5e5e5;
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #f59e0b;
        }
        .label {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }
        .message-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e5e5;
            white-space: pre-wrap;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥩 一佳香肉脯行</h1>
        <p>網站聯絡表單</p>
    </div>
    
    <div class="content">
        <h2>您收到一封新的聯絡訊息</h2>
        
        <div class="info-box">
            <div class="label">客戶姓名：</div>
            <div>{{ $name }}</div>
        </div>
        
        <div class="info-box">
            <div class="label">聯絡信箱：</div>
            <div>{{ $email }}</div>
        </div>
        
        <div class="info-box">
            <div class="label">訊息內容：</div>
            <div class="message-content">{{ $message }}</div>
        </div>
        
        <div class="info-box">
            <div class="label">收到時間：</div>
            <div>{{ date('Y-m-d H:i:s') }}</div>
        </div>
        
        <div class="footer">
            <p>📧 請及時回覆客戶查詢</p>
            <p>📞 門市電話：(089) 357-996</p>
            <p>📍 門市地址：台東縣台東市廣東路269號</p>
        </div>
    </div>
</body>
</html>
