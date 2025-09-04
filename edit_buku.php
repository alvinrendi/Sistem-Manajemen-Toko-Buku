<?php
session_start();
include "config/koneksi.php";

// Cek login & role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php?status=not_found");
    exit;
}

$id = (int) $_GET['id'];

// ==================== UPDATE PROSES ====================
$status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $harga   = (int) $_POST['harga'];
    $stok    = (int) $_POST['stok'];

   $update = mysqli_query($koneksi, "UPDATE buku 
    SET judul='$judul', penulis='$penulis', harga=$harga, stok=$stok 
    WHERE id=$id");

if ($update) {
    // update tabel harga juga
    mysqli_query($koneksi, "UPDATE harga SET harga=$harga WHERE buku_id=$id");

    $status = "success";
} else {
    $status = "error";
}

}

// Ambil data buku terbaru setelah update
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id=$id");
$buku = mysqli_fetch_assoc($result);

if (!$buku) {
    header("Location: admin_dashboard.php?status=not_found");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            background: #ffffffee;
        }
        .btn-primary {
            background: linear-gradient(45deg, #1a2980, #26d0ce);
            border: none;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #162263, #1fa3a3);
        }
        .alert {
            border-radius: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Notifikasi -->
    <?php if ($status == "success"): ?>
        <div class="alert alert-success text-center">✅ Buku berhasil disimpan!</div>
    <?php elseif ($status == "error"): ?>
        <div class="alert alert-danger text-center">❌ Gagal menyimpan perubahan!</div>
    <?php endif; ?>

    <div class="card p-4">
        <h3 class="mb-3 text-center">✏️ Edit Buku</h3>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= $buku['id'] ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Buku</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($buku['judul']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Penulis</label>
                <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($buku['penulis']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $buku['harga'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $buku['stok'] ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Kembali</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
