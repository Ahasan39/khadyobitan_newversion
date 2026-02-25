<!DOCTYPE html>
<html>
<head>
    <title>Payment Required</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8d7da;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .box {
            background: white;
            padding: 40px 50px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #ff4d4d;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        .title {
            font-size: 26px;
            font-weight: bold;
            color: #d60000;
        }
        .msg {
            margin-top: 15px;
            font-size: 18px;
        }
    </style>
</head>

<body>
<div class="box">
    <div class="title">⚠ Payment Required</div>
    <div class="msg">
        Your project payment deadline has passed.  
        Please complete your payment to continue using the service.
    </div>
</div>
</body>
</html>
