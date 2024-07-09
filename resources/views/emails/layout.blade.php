<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flame Email')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #414042;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #F15A22;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            background-color: #e8e8e8;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('/images/logo.svg') }}" width="200" alt="Flame Logo">
    </div>
    <div class="content">
        @yield('content')
    </div>
    <div class="footer"></div>
</div>
</body>
</html>
