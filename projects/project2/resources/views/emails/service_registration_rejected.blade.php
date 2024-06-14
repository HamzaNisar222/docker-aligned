<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Registration Rejected</title>
</head>
<body>
    <h2>Service Registration Rejected</h2>
    <p>Hello {{ $registration->user->name }},</p>
    <p>We regret to inform you that your application for {{ $registration->service->name }} has been rejected.</p>
    <p>Reason: {{ $registration->rejection_reason }}</p>
    <p>Please feel free to contact us if you have any questions.</p>
    <p>Best regards,</p>
    <p>Your Application Team</p>
</body>
</html>
