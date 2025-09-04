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

// Pastikan ada id buku
if (!isset($_GET['id'])) {
    echo "<script>alert('Buku tidak ditemukan!'); window.location='user_dashboard.php';</script>";
    exit;
}

$buku_id = intval($_GET['id']);
$buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$buku_id'"));

if (!$buku) {
    echo "<script>alert('Buku tidak ditemukan!'); window.location='user_dashboard.php';</script>";
    exit;
}

// Jika klik bayar
if (isset($_POST['bayar'])) {
    $metode = $_POST['metode'];

    // Tambahkan transaksi ke riwayat
   mysqli_query($koneksi, "INSERT INTO pembayaran (user_id, riwayat_id, metode_pembayaran, tanggal, status) 
                        VALUES ('$user_id', '$riwayat_id', '$metode', NOW(), 'pending')");


    $riwayat_id = mysqli_insert_id($koneksi);

    // Simpan ke pembayaran
    mysqli_query($koneksi, "INSERT INTO pembayaran (user_id, riwayat_id, metode_pembayaran, tanggal, status) 
                        VALUES ('$user_id', '$riwayat_id', '$metode', NOW(), 'dibayar')");


    // Kurangi stok buku
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id='$buku_id'");

    echo "<script>alert('Pembayaran berhasil!'); window.location='riwayat.php';</script>";
    exit;
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f6fa;
    margin: 0;
    padding: 0;
}

.payment-container {
    max-width: 700px;
    margin: 50px auto;
    background: #fff;
    border-radius: 16px;
    padding: 30px 40px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.payment-container:hover {
    transform: translateY(-3px);
}

.payment-container h1 {
    text-align: center;
    color: #0b74de;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: 700;
}

.transaction-card {
    display: flex;
    gap: 20px;
    background: #f7f9fc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #e2e6ea;
    align-items: center;
}

.transaction-card img {
    width: 140px;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.transaction-info {
    flex: 1;
}

.transaction-info h3 {
    font-size: 22px;
    margin: 0 0 12px;
    color: #333;
}

.transaction-info p {
    font-size: 16px;
    color: #555;
    margin: 5px 0;
}

label {
    font-weight: 600;
    color: #333;
    display: block;
    margin-bottom: 8px;
}

select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

select:focus {
    border-color: #0b74de;
    box-shadow: 0 0 8px rgba(11,116,222,0.3);
    outline: none;
}

.btn-bayar {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    background: linear-gradient(135deg, #0b74de, #1a96f0);
    transition: 0.3s ease-in-out;
}

.btn-bayar:hover {
    background: linear-gradient(135deg, #0961b7, #147ed6);
    transform: translateY(-2px);
}

.btn-back {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: #6c757d;
    color: white;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.btn-back:hover {
    background: #5a6268;
}

@media(max-width: 600px){
    .transaction-card {
        flex-direction: column;
        align-items: center;
    }
    .transaction-card img {
        width: 100%;
        height: auto;
    }
}
</style>

<div class="payment-container">
    <h1>💳 Pembayaran Buku</h1>

    <div class="transaction-card">
        <img src="assets/img/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
        <div class="transaction-info">
            <h3><?= $buku['judul'] ?></h3>
            <p>Harga: <b>Rp <?= number_format($buku['harga'],0,',','.') ?></b></p>
            <p>Stok: <?= $buku['stok'] ?></p>
        </div>
    </div>

    <form method="post">
        <label for="metode">Pilih Metode Pembayaran:</label>
        <select name="metode" id="metode" required>
            <option value="Transfer Bank">🏦 Transfer Bank</option>
            <option value="E-Wallet">📱 E-Wallet (OVO, GoPay, DANA)</option>
            <option value="COD">🚚 COD (Bayar di Tempat)</option>
        </select>

        <button type="submit" name="bayar" class="btn-bayar">💳 Bayar Sekarang</button>
    </form>

    <a href="user_dashboard.php" class="btn-back">🏠 Kembali ke Beranda</a>
</div>

<?php include "inc/footer.php"; ?>
