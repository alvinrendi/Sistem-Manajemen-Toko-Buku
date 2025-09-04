<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Promo Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            color: #333;
        }

        .header {
            background: #0b74ff;
            color: white;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }

        .promo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .card-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background: #0b74ff;
            color: white;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background: #0857c7;
        }
    </style>
</head>
<body>

    <div class="header">📚 Promo Buku</div>

    <div class="container">
        <h1>Promo & Info Terbaru</h1>

        <div class="promo-grid">
            <!-- Card 1 -->
            <div class="card">
                <img src="assets/promo1.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Diskon 25% Semua Buku</div>
                    <div class="card-date">4 - 7 September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <img src="assets/promo2.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Member Deals Khusus</div>
                    <div class="card-date">4 - 7 September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <img src="assets/promo3.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Pesta Literasi Nasional</div>
                    <div class="card-date">1 - 30 September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <img src="assets/promo4.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Diskon Back to Campus</div>
                    <div class="card-date">1 - 15 September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <img src="assets/promo5.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Buku Seru Paling Dicari</div>
                    <div class="card-date">September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="card">
                <img src="assets/promo6.jpg" alt="Promo Buku">
                <div class="card-content">
                    <div class="card-title">Science Day 2025</div>
                    <div class="card-date">10 - 20 September 2025</div>
                    <a href="#" class="btn">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
