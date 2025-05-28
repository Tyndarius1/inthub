<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
      <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #2c3e50;
        }
        .otp-box {
            background-color: #eaf4ff;
            color: #007bff;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            letter-spacing: 4px;
            margin: 20px 0;
        }
        .content p {
            font-size: 16px;
            color: #333333;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888888;
            text-align: center;
        }
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            .otp-box {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Internship Hub Logo" class="logo">
            <h2>Password Reset Token</h2>
        </div>
    <h2>Hello {{ $name }},</h2>
    <p>You requested to reset your password.</p>
    <p>Use the token below to reset your password:</p>
    <h3>{{ $token }}</h3>
    <p>This token will expire in 30 minutes.</p>
    <br>
    <small>If you didn't request this, you can ignore this email.</small>
</div>
</body>
</html>
