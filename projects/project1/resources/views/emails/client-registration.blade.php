<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Service Registration Request</title>
</head>
<body>
    <h2>Client Service Registration Request</h2>
    <p>Hello,{{$clientService->vendorServiceOffering->vendor->name}}</p>
    <p>Your {{$clientService->vendorServiceOffering->subservice->name}} has been Requested by  {{$clientService->client->name}} for date  {{$clientService->required_at}}.</p>
    <p>Please respond to this request as soon as possible</p>
    <p>Best regards,</p>
    <p>Your Application Team</p>
</body>
</html>
