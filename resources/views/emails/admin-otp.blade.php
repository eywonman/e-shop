<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            margin: 0;
            padding: 2rem;
        }

        .container {
            background-color: #ffffff;
            max-width: 480px;
            margin: 0 auto;
            padding: 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .otp {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin: 1rem 0;
        }

        .footer {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello Admin,</h2>

        <p>You requested to log in to the admin dashboard. Please use the following one-time password (OTP):</p>

        <p class="otp">{{ $otp }}</p>

        <p>Enter this code in the OTP verification screen to proceed. For your security, do not share this code with anyone.</p>

        <p class="footer">If you did not request this OTP, you can safely ignore this message.</p>
    </div>
</body>
</html>
