<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Registration Approved</title>
</head>
<body>
    <h2>Service Registration Approved</h2>
    <p>Hello {{ $registration->user->name }},</p>
    <p>Your application for {{ $registration->service->name }} has been approved.</p>
    <p>Thank you for registering with us.</p>
    <p>Best regards,</p>
    <p>Your Application Team</p>
</body>
</html>
