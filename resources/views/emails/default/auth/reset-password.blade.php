<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        /* Default Laravel email styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f7fafc;
            padding: 20px;
            margin: 0;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            font-size: 16px;
            color: #4a5568;
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-header h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .email-content {
            margin-bottom: 20px;
        }

        .reset-button {
            display: inline-block;
            background-color: #3182ce;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #718096;
            margin-top: 20px;
        }

        .footer a {
            color: #3182ce;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                padding: 20px;
            }

            .reset-button {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Password Reset Request</h1>
    </div>

    <div class="email-content">
        <p>Hello,</p>
        <p>We received a request to reset the password for your account. You can reset your password by clicking the button below:</p>
        <p>
            <a href="{{ $url }}" class="reset-button">Reset Password</a>
        </p>
        <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>