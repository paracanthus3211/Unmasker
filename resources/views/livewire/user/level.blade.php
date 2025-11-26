<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level {{ request()->route('num') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .container-custom {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .level-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .level-description {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.3);
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(106, 17, 203, 0.4);
            color: white;
        }
        
        .level-badge {
            background: linear-gradient(135deg, #00b09b, #96c93d);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 176, 155, 0.3);
        }
        
        .game-placeholder {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            border: 2px dashed #dee2e6;
        }
        
        .game-icon {
            font-size: 4rem;
            color: #6a11cb;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-custom text-center">
        <div class="level-badge">
            Level {{ request()->route('num') }}
        </div>
        
        <h1 class="level-title">Selamat Datang di Level {{ request()->route('num') }}!</h1>
        
        <p class="level-description">
            Halaman ini nanti bisa kamu isi dengan soal, animasi, atau scene game sesuai dengan level yang sedang dimainkan.
        </p>
        
        <div class="game-placeholder">
            <div class="game-icon">🎮</div>
            <h4 style="color: #2c3e50;">Area Game</h4>
            <p style="color: #7f8c8d;">Konten game akan ditampilkan di sini</p>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
            <!-- PERBAIKAN 1: Menggunakan path langsung untuk tombol kembali -->
            <a href="/user/quizz" class="btn btn-custom">
                Kembali ke Daftar Level
            </a>
            
            <button class="btn btn-outline-primary" style="border-radius: 50px; padding: 12px 30px; font-weight: 600;">
                Mulai Game
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>