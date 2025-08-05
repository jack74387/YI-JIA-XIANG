<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>感謝您的聯絡 - 一佳香肉脯行</title>
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
        .highlight-box {
            background-color: #fef3c7;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .contact-info {
            background-color: white;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #e5e5e5;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .emoji {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥩 一佳香肉脯行</h1>
        <p>感謝您的聯絡</p>
    </div>
    
    <div class="content">
        <h2>親愛的 {{ $name }} 您好，</h2>
        
        <div class="highlight-box">
            <p><span class="emoji">✅</span> <strong>我們已收到您的訊息！</strong></p>
            <p>感謝您對一佳香肉脯行的關注與支持。我們會在 24 小時內回覆您的查詢。</p>
        </div>
        
        <div class="contact-info">
            <h3><span class="emoji">📞</span> 如有緊急事項，歡迎直接聯絡我們：</h3>
            <ul>
                <li><strong>門市電話：</strong>(089) 357-996</li>
                <li><strong>營業時間：</strong>09:00-20:00</li>
                <li><strong>門市地址：</strong>台東縣台東市廣東路265號</li>
                <li><strong>Facebook：</strong><a href="https://www.facebook.com/yiijiaxiang/?locale=zh_TW" style="color: #f59e0b;">一佳香肉脯行粉絲專頁</a></li>
            </ul>
        </div>
        
        <div class="highlight-box">
            <p><span class="emoji">🎁</span> <strong>特別優惠</strong></p>
            <p>首次購買可享 9 折優惠，歡迎到門市品嚐我們精選的台東在地美味！</p>
        </div>
        
        <div class="footer">
            <p>此為系統自動發送的確認信件，請勿直接回覆。</p>
            <p>如需協助，請直接致電門市或透過 Facebook 私訊聯絡。</p>
            <br>
            <p><strong>一佳香肉脯行</strong> - 台東在地好味道</p>
        </div>
    </div>
</body>
</html>
