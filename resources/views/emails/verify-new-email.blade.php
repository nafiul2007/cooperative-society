<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Your Account Credentials</title>
</head>

<body>
    <p>Dear {{ $memberName }},</p>

    <p>Click the link below to verify your new email address:</p>

    <a href="{{ url('/email/change/verify/' . $token) }}">
        Verify Email
    </a>

    <p>This link will expire soon for security reasons.</p>

    <p>Thank you,<br>Cooperative Society Team</p>
</body>

</html>
