<?php
session_start();
include "config/koneksi.php";

// Cek login & role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter id
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php?status=not_found");
    exit;
}

$id = (int) $_GET['id'];

// Hapus data buku
$query = "DELETE FROM buku WHERE id=$id";
if (mysqli_query($koneksi, $query)) {
    header("Location: admin_dashboard.php?status=deleted");
} else {
    header("Location: admin_dashboard.php?status=error");
}
