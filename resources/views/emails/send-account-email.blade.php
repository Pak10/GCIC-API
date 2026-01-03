<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCIC - OTP</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <link href="assets/css/app.min.css" rel="stylesheet">
    <link href="assets/css/custom.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 7px;
            box-shadow: 0 3px 15px rgba(6, 26, 75, 0.06);
            text-align: center;
        }

        .email-container h2 {
            font-weight: 500;
            margin-bottom: 10px;
        }

        .email-text {
            color: #878a99;
            font-size: 15px;
            line-height: 1.5;
        }

        .email-button {
            display: inline-block;
            background: #03315f;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
        }

        .email-footer {
            font-size: 14px;
            color: #878a99;
            margin-top: 20px;
            border-top: 1px solid #e9ebec;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2>Hello {{$user->name}},</h2>
        
        <p class="email-text">You are receiving this because an account has been created on the GCIC platform.</p>

        <p class="email-text">Temporary password : {{$password}}.</p>

        <p class="email-text" style="padding-top: 10px;"> If you did not request this , simply ignore this
            email and contact your support team immeadiately. 

        <p class="email-text">Best Regards,
            GCIC Team</p>
    </div>
</body>

</html>