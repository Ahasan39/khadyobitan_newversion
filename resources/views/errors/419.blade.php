<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>419 - Page Expired</title>
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
      color: #f39c12;
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.1); opacity: 0.8; }
      100% { transform: scale(1); opacity: 1; }
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
      border: 1px solid #f39c12;
      color: #f39c12;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    a.btn-custom:hover {
      background: #f39c12;
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>419</h1>
  <h2>Page Expired</h2>
  <p>Your session has expired. Please refresh the page or go back to continue.</p>
  <div>
    <a href="javascript:history.back()" class="btn-custom me-2">Go Back</a>
    <a href="/" class="btn-custom">Home Page</a>
  </div>
</body>
</html>

