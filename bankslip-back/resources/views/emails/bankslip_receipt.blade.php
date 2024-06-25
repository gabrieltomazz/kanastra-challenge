<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanastra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .bank-slip {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bank Slip</h2>
        <p>Dear {{ $bankslip->name }},</p>
        <p>We are writing to inform you that your bank slip is attached to this email. Please find the attached document for your records.</p>
        <div class="bank-slip">
            <a href="https://localhost/bank-slip.pdf" target="_blank" style="padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Download Here</a>
        </div>
        <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
        <p>Thank you for choosing our services.</p>
        <div class="footer">
            <p>This is an automated message. Please do not reply.</p>
            <p>&copy; 2024 Kasastra. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
