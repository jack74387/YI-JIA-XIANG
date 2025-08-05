<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重設密碼 - 一佳香肉脯行</title>
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
        .reset-button {
            display: inline-block;
            background-color: #f59e0b;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .reset-button:hover {
            background-color: #d97706;
        }
        .security-notice {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #f59e0b;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .token-info {
            background-color: #e5e7eb;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥩 一佳香肉脯行</h1>
        <p>密碼重設請求</p>
    </div>
    
    <div class="content">
        <h2>親愛的會員 您好，</h2>
        
        <p>我們收到了您重設密碼的請求。請點選下方按鈕來重設您的密碼：</p>
        
        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="reset-button">
                🔐 重設我的密碼
            </a>
        </div>
        
        <div class="security-notice">
            <h4>🔒 安全提醒</h4>
            <ul>
                <li>這個重設連結將在 <strong>60 分鐘</strong> 後失效</li>
                <li>如果您沒有申請重設密碼，請忽略此郵件</li>
                <li>請勿將此連結分享給任何人</li>
            </ul>
        </div>
        
        <p><strong>如果上方按鈕無法點選，請複製以下連結到瀏覽器：</strong></p>
        <div class="token-info">
            {{ $resetUrl }}
        </div>
        
        <div class="footer">
            <p>如果您有任何問題，請聯絡我們：</p>
            <p>📞 電話：(089) 357-996</p>
            <p>📍 地址：台東縣台東市廣東路265號</p>
            <p>🕒 營業時間：09:00-20:00</p>
            <br>
            <p>此為系統自動發送的郵件，請勿直接回覆。</p>
            <p><strong>一佳香肉脯行</strong> - 台東在地好味道</p>
        </div>
    </div>
</body>
</html>
