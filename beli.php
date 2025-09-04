<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: daftar_buku.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$buku_id = $_GET['id'];

// ambil data buku
$q = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$buku_id'");
$buku = mysqli_fetch_assoc($q);

if (!$buku) {
    echo "<script>alert('Buku tidak ditemukan!'); window.location='daftar_buku.php';</script>";
    exit;
}

// cek stok
if ($buku['stok'] <= 0) {
    echo "<script>alert('Stok buku habis!'); window.location='daftar_buku.php';</script>";
    exit;
}

// kurangi stok buku
mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id='$buku_id'");

// masukkan ke tabel riwayat
$jumlah = 1;
$total = $buku['harga'] * $jumlah;
mysqli_query($koneksi, "INSERT INTO riwayat (user_id, buku_id, jumlah, total, status, tanggal) 
                        VALUES ('$user_id', '$buku_id', '$jumlah', '$total', 'pending', NOW())");

echo "<script>alert('Buku berhasil dibeli! Silakan cek riwayat belanja.'); window.location='riwayat.php';</script>";
