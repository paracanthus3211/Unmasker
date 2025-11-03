<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>3 Cards Gradient Move + Neon Buttons</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: radial-gradient(circle at top left, #1a0028, #0d0015 80%);
      color: #e8d9ff;
      font-family: "Poppins", sans-serif;
    }

    h2 {
      color: #e8d9ff;
      text-shadow: 0 0 10px #b266ff, 0 0 20px #9b59ff;
    }

    /* Efek gradient bergerak */
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Kartu dengan gradient animasi */
    .card {
      border: none;
      border-radius: 15px;
      color: #fff;
      background: linear-gradient(-45deg, #a34cff, #b266ff, #8e44ad, #6a0dad);
      background-size: 400% 400%;
      animation: gradientMove 10s ease infinite;
      box-shadow: 0 0 25px rgba(180, 80, 255, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 0 40px rgba(200, 100, 255, 0.5);
    }

    .card img {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    /* Tombol Neon Glowing dengan Gradient Bergerak */
    .btn-neon {
      position: relative;
      display: inline-block;
      width: 100%;
      padding: 10px 25px;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: bold;
      text-decoration: none;
      border: 2px solid #ff0080;
      border-radius: 30px;
      background: linear-gradient(135deg, #ff0080, #7928ca, #00d2ff);
      background-size: 300% 300%;
      animation: gradientMove 6s ease infinite;
      transition: 0.3s ease;
      overflow: hidden;
      box-shadow: 0 0 5px #ff0080, 0 0 15px #7928ca;
    }

    .btn-neon:hover {
      color: #fff;
      background: linear-gradient(135deg, #ff0080, #7928ca, #00d2ff);
      background-size: 300% 300%;
      box-shadow: 0 0 15px #ff0080, 0 0 25px #7928ca, 0 0 35px #00d2ff;
      transform: translateY(-3px) scale(1.05);
    }
  </style>
</head>

<body>
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container py-5">
      <h2 class="text-center mb-4 fw-bold">Library</h2>

      <div class="row g-4 justify-content-center">
        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://via.placeholder.com/600x300?text=Rukun+Islam" class="card-img-top" alt="">
            <div class="card-body text-center">
              <h5 class="card-title">test</h5>
              <p class="card-text">lorem ipsum</p>
              <button class="btn-neon">Start</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://via.placeholder.com/600x300?text=Sejarah+Nabi" class="card-img-top" alt="">
            <div class="card-body text-center">
              <h5 class="card-title">fisiognomi</h5>
              <p class="card-text">lorem ipsum</p>
              <button class="btn-neon">Start</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://via.placeholder.com/600x300?text=Akhlak+dan+Etika" class="card-img-top" alt="">
            <div class="card-body text-center">
              <h5 class="card-title">test</h5>
              <p class="card-text">test</p>
              <button class="btn-neon">Start</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
