<!DOCTYPE html>
<html>
<head>
    <title>Late Scan Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: auto;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #265073;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
        }
        .email-footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <a href="https://amtis.com.my/amtisv4/">
                <img src="https://amtis.com.my/amtisv4/wp-content/uploads/2020/10/amtis-logo-2.png" alt="AMTIS Logo">
            </a>
            <h1>Late Scan Notification</h1>
        </div>

        <!-- Body Section -->
        <div class="email-body">
            <p>Dear {{ $user->name }},</p>
            <p>You are late to scan the QR code today. Please scan it as soon as possible to ensure your attendance is recorded.</p>
            <p>Thank you.</p>
        </div>

        <!-- Footer Section -->
        <div class="email-footer">
            &copy; {{ date('Y') }} AMTIS Solution Sdn Bhd. All rights reserved.
        </div>
    </div>
</body>
</html>
