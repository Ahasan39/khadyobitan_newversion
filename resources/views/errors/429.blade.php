<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>429 - Too Many Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #ffffff;
      color: #222;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      flex-direction: column;
      text-align: center;
    }
    h1 {
      font-size: 8rem;
      font-weight: 700;
      color: #3498db;
      animation: bounce 2s infinite;
    }
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-20px); }
      60% { transform: translateY(-10px); }
    }
    h2 {
      font-size: 1.8rem;
      margin-bottom: 10px;
      color: #444;
    }
    p {
      font-size: 1rem;
      color: #666;
      margin-bottom: 25px;
    }
    a.btn-custom {
      display: inline-block;
      padding: 10px 25px;
      border: 1px solid #3498db;
      color: #3498db;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    a.btn-custom:hover {
      background: #3498db;
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>429</h1>
  <h2>Too Many Requests</h2>
  <p>You’ve sent too many requests in a short time. Please wait a bit before trying again.</p>
  <div>
    <a href="javascript:history.back()" class="btn-custom me-2">Go Back</a>
    <a href="/" class="btn-custom">Home Page</a>
  </div>
</body>
</html>