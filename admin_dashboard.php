<?php 
session_start();
include "config/koneksi.php";

// cek login & role
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header("Location: user_dashboard.php");
    exit;
}

include "inc/header.php";
?>

<style>
    .main-content {
    margin-left: 240px;
    padding: 30px;
    padding-bottom: 80px; /* ruang untuk footer */
}
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f9;
        margin: 0;
    }
    .sidebar {
        width: 220px;
        height: 100vh;
        background: #343a40;
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
        border-bottom: 1px solid #495057;
        padding-bottom: 10px;
    }
    .sidebar p {
        text-align: center;
        font-size: 14px;
        margin-bottom: 20px;
        color: #bbb;
    }
    .sidebar a {
        display: block;
        padding: 12px 15px;
        color: #ddd;
        text-decoration: none;
        margin: 8px 0;
        border-radius: 8px;
        transition: 0.3s;
    }
    .sidebar a:hover {
        background: #007bff;
        color: #fff;
    }
    .main-content {
        margin-left: 240px;
        padding: 30px;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        text-align: center;
    }
    .card h3 { margin: 0; color: #333; }
    .card p { font-size: 24px; margin-top: 10px; color: #007bff; }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    table th, table td { padding: 12px 15px; text-align: center; }
    table th { background: #007bff; color: white; }
    table tr:nth-child(even) { background: #f9f9f9; }
    img.thumbnail { width: 60px; height: auto; border-radius: 6px; }
    .aksi-btn a {
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        margin: 0 3px;
        font-size: 14px;
    }
    .edit-btn { background: #28a745; }
    .hapus-btn { background: #dc3545; }
</style>

<div class="sidebar">
    <h2>📚 Admin Panel</h2>
    <p>👤 <?= htmlspecialchars($_SESSION['username']); ?> (<?= $_SESSION['role']; ?>)</p>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="tambah_buku.php">Tambah Buku</a>
    <a href="admin_harga.php">Harga Buku</a>
    <a href="admin_pembayaran.php">Pembayaran</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h1>
    

    <!-- Statistik -->
    <div class="stats">
        <div class="card">
            <h3>Total Buku</h3>
            <p>
                <?php 
                $q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
                $d = mysqli_fetch_assoc($q);
                echo $d['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Total Penulis</h3>
            <p>
                <?php 
                $q = mysqli_query($koneksi, "SELECT COUNT(DISTINCT penulis) AS total FROM buku");
                $d = mysqli_fetch_assoc($q);
                echo $d['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Total Stok</h3>
            <p>
                <?php 
                $q = mysqli_query($koneksi, "SELECT SUM(stok) AS total FROM buku");
                $d = mysqli_fetch_assoc($q);
                echo $d['total'];
                ?>
            </p>
        </div>
    </div>

    <!-- Data Buku -->
    <table>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id'];
    echo "<tr>
        <td><img src='assets/img/".htmlspecialchars($row['gambar'])."' class='thumbnail' alt='gambar buku'></td>
        <td>".htmlspecialchars($row['judul'])."</td>
        <td>".htmlspecialchars($row['penulis'])."</td>
        <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
        <td>{$row['stok']}</td>
        <td class='aksi-btn'>
            <a href='edit_buku.php?id={$id}' class='edit-btn'>Edit</a>
            <a href='delete_buku.php?id={$id}' class='hapus-btn' onclick=\"return confirm('Yakin ingin menghapus buku ini?')\">Hapus</a>
        </td>
    </tr>";
}
        ?>
    </table>
</div>

<?php include "inc/footer.php"; ?>
