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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .button {
            display: inline-block;
            background: #667eea;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to SchoolCode Africa!</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>
        
        <p>We are excited to have you join our online learning platform. Your account has been successfully created!</p>
        
        <p><strong>Your Account Details:</strong></p>
        <ul>
            <li><strong>Name:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>School:</strong> {{ $user->school }}</li>
            <li><strong>Class:</strong> {{ $user->class }}</li>
        </ul>
        
        <p>You can now access all our courses and learning materials. Start your learning journey today!</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard</a>
        </p>
        
        <p>If you have any questions, feel free to reach out to our support team.</p>
        
        <p>Happy Learning!<br>
        <strong>The SchoolCode Africa Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} SoftPen Technologies. All rights reserved.</p>
    </div>
</body>
</html>