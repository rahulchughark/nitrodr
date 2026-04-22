<html>
<head>
    <title>Account Credentials</title>
</head>
<body>
    <h1>Welcome, {{ $emailData['name'] }}!</h1>
    <p>Here are your account credentials:</p>
    <p><strong>Email:</strong> {{ $emailData['loginId'] }}</p>
    <p><strong>Password:</strong> {{ $emailData['password'] }}</p>
    <p><strong>Url:</strong> {{ $emailData['url'] }}</p>
    <p>Best regards,</p>
    <p>Team ICT</p>
</body>
</html>