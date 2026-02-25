<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>503 - Service Unavailable</title>
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
      color: #e67e22;
      animation: float 2s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
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
      border: 1px solid #e67e22;
      color: #e67e22;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    a.btn-custom:hover {
      background: #e67e22;
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>503</h1>
  <h2>Service Unavailable</h2>
  <p>Our service is temporarily unavailable. Please try again later.</p>
  <div>
    <a href="javascript:history.back()" class="btn-custom me-2">Go Back</a>
    <a href="/" class="btn-custom">Home Page</a>
  </div>
</body>
</html>