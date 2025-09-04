<?php
include "config/koneksi.php";

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    die("ID buku tidak ditemukan.");
}

$id = (int) $_GET['id'];

// Hapus buku (harga otomatis ikut terhapus karena ON DELETE CASCADE)
$stmt = $koneksi->prepare("DELETE FROM buku WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirect balik ke halaman admin buku
header("Location: admin_dashboard.php");
exit;
?>
