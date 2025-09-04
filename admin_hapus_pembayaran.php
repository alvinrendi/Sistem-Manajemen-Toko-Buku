<?php
include "config/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($koneksi, "DELETE FROM pembayaran WHERE id='$id'");
    header("Location: admin_pembayaran.php");
}
?>
