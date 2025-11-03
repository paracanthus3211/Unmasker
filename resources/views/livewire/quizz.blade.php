<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tombol Animasi Level Zigzag</title>
  <style>
    body {
      margin: 0;
      background-color: #111;
      overflow-x: hidden;
      font-family: sans-serif;
    }

    .quiz-page {
      position: relative;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      overflow-y: auto; /* aktifkan scroll */
      padding-top: 50px;
    }

    .level-container {
      position: relative;
      width: 320px; /* area zigzag */
      height: 1600px; /* cukup tinggi untuk scroll */
    }

    .level-btn {
      position: absolute;
      width: 130px;
      height: 130px;
      background-image: url('/assets/img/level-button.jpeg');
      background-size: cover;
      background-position: center;
      border: none;
      border-radius: 50%;
      outline: none;
      cursor: pointer;
      box-shadow: 0 8px 0 #555, 0 12px 20px rgba(0, 0, 0, 0.5);
      transition: all 0.15s ease;
    }

    .level-btn:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }

    .level-btn:active {
      transform: translateY(6px) scale(0.95);
      box-shadow: 0 2px 0 #333, 0 6px 10px rgba(0, 0, 0, 0.5);
      filter: brightness(0.9);
    }

    /* Zigzag: kiri-kanan bergantian, dengan jarak antar tombol */
    .level1  { top: 0%;   left: 0; }
    .level2  { top: 10%;  right: 0; }
    .level3  { top: 20%;  left: 0; }
    .level4  { top: 30%;  right: 0; }
    .level5  { top: 40%;  left: 0; }
    .level6  { top: 50%;  right: 0; }
    .level7  { top: 60%;  left: 0; }
    .level8  { top: 70%;  right: 0; }
    .level9  { top: 80%;  left: 0; }
    .level10 { top: 90%;  right: 0; }
  </style>
</head>
<body>
  <div class="quiz-page">
    <div class="level-container">
      @for ($i = 1; $i <= 10; $i++)
        <button class="level-btn level{{ $i }}" onclick="goToLevel({{ $i }})"></button>
      @endfor
    </div>
  </div>

  <script>
    function goToLevel(level) {
      window.location.href = `/quizz/level${level}`;
    }
  </script>
</body>
</html>
