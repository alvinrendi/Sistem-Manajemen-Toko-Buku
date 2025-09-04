<?php 
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}
include "inc/header.php";

// Ambil user_id
$user_id = $_SESSION['user_id'];

// Hapus satu data
if (isset($_GET['hapus'])) {
    $hapus_id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM riwayat WHERE id='$hapus_id' AND user_id='$user_id'");
    echo "<script>alert('Riwayat berhasil dihapus!'); window.location='riwayat.php';</script>";
    exit;
}

// Hapus semua data
if (isset($_GET['hapus_semua'])) {
    mysqli_query($koneksi, "DELETE FROM riwayat WHERE user_id='$user_id'");
    echo "<script>alert('Semua riwayat berhasil dihapus!'); window.location='riwayat.php';</script>";
    exit;
}

// Ambil riwayat user
$result = mysqli_query($koneksi, "SELECT r.*, b.judul, b.gambar 
                                  FROM riwayat r 
                                  JOIN buku b ON r.buku_id = b.id 
                                  WHERE r.user_id='$user_id'
                                  ORDER BY r.tanggal DESC");
?>

<style>
    table {
        width: 100%;
        background: white;
        border-radius: 8px;
        border-collapse: collapse;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 10px;
        text-align: center;
    }
    th {
        background: #007bff;
        color: white;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    img {
        border-radius: 6px;
    }
    .btn-hapus {
        display: inline-block;
        padding: 6px 12px;
        background: #dc3545;
        color: white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s ease-in-out;
    }
    .btn-hapus:hover {
        background: #b02a37;
        transform: scale(1.05);
    }
    .btn-hapus-semua {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 18px;
        background: #6c757d;
        color: white;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s ease-in-out;
    }
    .btn-hapus-semua:hover {
        background: #5a6268;
        transform: scale(1.05);
    }
    .btn-beranda {
        display: inline-block;
        margin-top: 15px;
        margin-left: 10px;
        padding: 10px 18px;
        background: #28a745;
        color: white;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s ease-in-out;
    }
    .btn-beranda:hover {
        background: #218838;
        transform: scale(1.05);
    }
</style>

<div class="main-content">
    <h1>🛒 Riwayat Belanja</h1>
    <table>
        <tr>
            <th>Gambar</th>
            <th>Judul Buku</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><img src="assets/img/<?= $row['gambar'] ?>" width="60"></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>
                    <a href="riwayat.php?hapus=<?= $row['id'] ?>" 
                       onclick="return confirm('Yakin ingin hapus riwayat ini?')" 
                       class="btn-hapus">🗑 Hapus</a>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7" style="color:red; font-weight:bold;">Tidak ada riwayat belanja.</td>
            </tr>
        <?php } ?>
    </table>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <a href="riwayat.php?hapus_semua=1" 
           onclick="return confirm('Yakin ingin hapus semua riwayat belanja?')" 
           class="btn-hapus-semua">🧹 Hapus Semua Riwayat</a>
    <?php } ?>
    
    <a href="user_dashboard.php" class="btn-beranda">🏠 Kembali ke Beranda</a>
</div>
