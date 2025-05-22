<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
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
            <h2>Email Verification</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for signing up. Please use the following One-Time Password (OTP) to verify your account:</p>
            <div class="otp-box">{{ $otp }}</div>
            <p>This code will expire in <strong>5 minutes</strong>. If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Internship Hub. All rights reserved.
        </div>
    </div>

</body>
</html>
