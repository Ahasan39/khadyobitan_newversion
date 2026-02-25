<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>401 - Unauthorized</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #fdfdfd, #f3f4f6);
      font-family: 'Inter', sans-serif;
      overflow: hidden;
    }

    .error-section {
      text-align: center;
      padding: 50px 30px;
      border-radius: 16px;
      border: 1px solid #e5e5e5;
      background: #fff;
      animation: fadeIn 0.8s ease-in-out;
    }

    .error-code {
      font-size: 120px;
      font-weight: 700;
      background: linear-gradient(90deg, #ff9966, #ff5e62);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: 2px;
      animation: float 3s ease-in-out infinite;
    }

    .error-title {
      font-size: 26px;
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
    }

    .error-text {
      font-size: 16px;
      color: #666;
      margin-bottom: 30px;
    }

    .btn-custom {
      border-radius: 25px;
      padding: 10px 22px;
      font-weight: 600;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .btn-outline-custom {
      border: 2px solid #ff5e62;
      color: #ff5e62;
    }

    .btn-outline-custom:hover {
      background: #ff5e62;
      color: #fff;
      transform: translateY(-2px);
    }

    .btn-fill-custom {
      background: linear-gradient(90deg, #ff9966, #ff5e62);
      color: #fff;
      border: none;
    }

    .btn-fill-custom:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="error-section">
    <div class="error-code">401</div>
    <div class="error-title">Unauthorized Access</div>
    <div class="error-text">You don’t have permission to view this page. Please login or go back.</div>

    <div class="d-flex justify-content-center gap-3">
      <a href="{{ url()->previous() }}" class="btn btn-outline-custom btn-custom">Go Back</a>
      <a href="{{ url('/') }}" class="btn btn-fill-custom btn-custom">Go Home</a>
    </div>
  </div>

</body>
</html>
