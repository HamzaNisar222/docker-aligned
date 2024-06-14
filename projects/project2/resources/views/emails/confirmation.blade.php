<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
</head>
<body>
    <p>Please click the following link to confirm your email:</p>
    <a href="{{ $confirmationUrl }}">{{ $confirmationUrl }}</a>
</body>
</html>
