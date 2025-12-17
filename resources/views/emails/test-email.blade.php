<!DOCTYPE html>
<html>
<head>
    <title>Test Email</title>
</head>
<body>
    <h1>Test Email</h1>
    <p>{{ $message }}</p>
    <p>If you received this email, your email configuration is working correctly.</p>

    <p>Thank you,<br>
    {{ config('app.name') }}</p>
</body>
</html>
