<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aduan Status Updated</title>
</head>
<body>
    <h1>Status Update for Aduan: {{ $aduan->judul }}</h1>
    <p>Dear User,</p>
    <p>Your aduan has been updated.</p>
    <p>Old Status: {{ $oldStatus }}</p>
    <p>New Status: {{ $aduan->status }}</p>
    <p>Thank you for using our service!</p>
</body>
</html>
