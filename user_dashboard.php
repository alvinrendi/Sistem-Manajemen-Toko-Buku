<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

include "inc/header.php";
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f9;
        margin: 0;
    }

    .sidebar {
        width: 200px;
        height: 100vh;
        background: #007bff;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
    }

    .sidebar h2 {
        font-size: 20px;
        margin-bottom: 25px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        padding-bottom: 10px;
    }

    .sidebar a {
        display: block;
        padding: 10px 12px;
        color: #f1f1f1;
        text-decoration: none;
        margin: 8px 0;
        border-radius: 6px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #0056b3;
        color: #fff;
    }

    .main-content {
        margin-left: 220px;
        padding: 30px;
    }

    .book-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    .book-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 15px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 380px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .book-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
    }

    .book-card h3 {
        font-size: 16px;
        margin: 10px 0 5px;
        color: #333;
        min-height: 40px;
    }

    .book-card p {
        margin: 5px 0;
        color: #555;
        font-size: 14px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: bold;
        margin-left: 6px;
        vertical-align: middle;
    }

    .badge-tersedia {
        background: #28a745;
        color: white;
    }

    .badge-habis {
        background: #dc3545;
        color: white;
    }

    .btn {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }

    .btn-detail {
        background: #28a745;
        color: white;
    }

    .btn-beli {
        background: #ffc107;
        color: black;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn:hover:not(:disabled) {
        opacity: 0.8;
    }
</style>

<div class="sidebar">
    <h2>👤 User Panel</h2>
    <a href="user_dashboard.php">🏠 Home</a>
    <a href="promo_buku.php">📚 Promo Buku</a>
    <a href="user_pembayaran.php">💳 Pembayaran</a>
    <a href="riwayat.php">🛒 Riwayat Belanja</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>Halo, <?= $_SESSION['username'] ?> 👋</h1>
    <p>Selamat datang di toko buku online. Silakan pilih buku favoritmu!</p>

    <div class="book-list">
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            $badge = $row['stok'] > 0 ? "<span class='badge badge-tersedia'>Tersedia</span>" : "<span class='badge badge-habis'>Habis</span>";
            echo "
            <div class='book-card'>
                <img src='assets/img/{$row['gambar']}' alt='buku'>
                <h3>{$row['judul']} $badge</h3>
                <p><b>Rp " . number_format($row['harga'],0,',','.') . "</b></p>
                <p>Stok: {$row['stok']}</p>
                <div>
                    <a href='detail_buku.php?id={$row['id']}' class='btn btn-detail'>Detail</a>";
            if ($row['stok'] > 0) {
                echo "<a href='user_pembayaran.php?id={$row['id']}' class='btn btn-beli'>Beli</a>";
            } else {
                echo "<button class='btn btn-beli' disabled>Stok Habis</button>";
            }
            echo "
                </div>
            </div>";
        }
        ?>
    </div>
</div>

<?php include "inc/footer.php"; ?>
