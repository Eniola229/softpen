<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .bonus-amount {
            background: #fff;
            border: 3px dashed #11998e;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 10px;
        }
        .bonus-amount h2 {
            color: #11998e;
            font-size: 36px;
            margin: 0;
        }
        .button {
            display: inline-block;
            background: #11998e;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .celebration {
            font-size: 48px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="celebration">🎉</div>
        <h1>Congratulations!</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>
        
        <p>Great news! As a welcome gift, we've added a special bonus to your wallet!</p>
        
        <div class="bonus-amount">
            <p style="margin: 0; font-size: 18px; color: #666;">Welcome Bonus</p>
            <h2>₦2,000.00</h2>
            <p style="margin: 0; color: #666;">Credited to your wallet</p>
        </div>
        
        <p><strong>What can you do with your bonus?</strong></p>
        <ul>
            <li>Purchase any course on our platform</li>
            <li>Access premium learning materials</li>
            <li>Enroll in multiple courses</li>
            <li>Start learning immediately</li>
        </ul>
        
        <p>This bonus is our way of saying thank you for joining SchoolCode. We're committed to helping you achieve your educational goals!</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/dashboard') }}" class="button">Start Learning Now</a>
        </p>
        
        <p><strong>Important:</strong> Your bonus has been automatically added to your account and is ready to use.</p>
        
        <p>Best regards,<br>
        <strong>The SchoolCode Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} SoftPen Technologies. All rights reserved.</p>
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>