<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Account Credentials</title>
</head>
<body>
    <p>Dear {{ $memberName }},</p>

    <p>Your account has been created in <strong>{{ config('app.name') }}</strong>. Here are your login credentials:</p>

    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Please login and change your password after your first login.</p>

    <p>Thank you,<br>Cooperative Society Team</p>
</body>
</html>
