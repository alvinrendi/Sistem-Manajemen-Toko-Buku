<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include "inc/header.php";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
}

.filter-form {
    margin: 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-form select, .filter-form button {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.payment-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.payment-table th,
.payment-table td {
    padding: 12px 15px;
    text-align: left;
}

.payment-table th {
    background: #007bff;
    color: white;
}

.payment-table tr:nth-child(even) {
    background: #f9f9f9;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 6px;
    color: white;
    font-weight: bold;
}

.status-pending { background: #ffc107; }
.status-dibayar { background: #28a745; }
.status-gagal { background: #dc3545; }

.btn-status, .btn-detail {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: 0.3s;
    margin-right: 5px;
}

.btn-dibayar { background: #28a745; }
.btn-gagal { background: #dc3545; }
.btn-detail { background: #0b74de; }

.btn-dibayar:hover, .btn-gagal:hover, .btn-detail:hover { opacity: 0.8; }
</style>

<h1>💳 Data Pembayaran User</h1>

<form method="get" class="filter-form">
    <label for="status_filter">Filter Status:</label>
    <select name="status" id="status_filter">
        <option value="">Semua</option>
        <option value="pending" <?= (isset($_GET['status']) && $_GET['status']=='pending')?'selected':'' ?>>Pending</option>
        <option value="dibayar" <?= (isset($_GET['status']) && $_GET['status']=='dibayar')?'selected':'' ?>>Dibayar</option>
        <option value="gagal" <?= (isset($_GET['status']) && $_GET['status']=='gagal')?'selected':'' ?>>Gagal</option>
    </select>
    <button type="submit">Filter</button>
</form>

<table class="payment-table">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Buku</th>
    <th>Metode</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php
$status_filter = $_GET['status'] ?? '';

$query = "
SELECT p.id, p.riwayat_id, p.metode_pembayaran, p.tanggal, p.status, 
       r.user_id, r.buku_id, r.total, b.judul AS buku_judul, u.username
FROM pembayaran p
LEFT JOIN riwayat r ON p.riwayat_id = r.id
LEFT JOIN buku b ON r.buku_id = b.id
LEFT JOIN user u ON r.user_id = u.id

";


if($status_filter != ''){
    $query .= " WHERE p.status='$status_filter'";
}

$query .= " ORDER BY p.tanggal DESC";

$result = mysqli_query($koneksi, $query);

while($row = mysqli_fetch_assoc($result)){
    $status_class = '';
    if($row['status']=='pending') $status_class='status-pending';
    elseif($row['status']=='dibayar') $status_class='status-dibayar';
    elseif($row['status']=='gagal') $status_class='status-gagal';

    $username = $row['username'] ?? "User #{$row['user_id']}";
    $buku_judul = $row['buku_judul'] ?? "Buku #{$row['buku_id']}";
    $metode = $row['metode'] ?? "-";
    $tanggal = $row['tanggal'] ?? "-";
    $status = $row['status'] ?? "-";

    echo "<tr>
        <td>{$row['metode_pembayaran']}</td>
        <td>{$username}</td>
        <td>{$buku_judul}</td>
        <td>{$metode}</td>
        <td>{$tanggal}</td>
        <td><span class='status-badge $status_class'>{$status}</span></td>
        <td>";

    // Tombol aksi
    if($status=='pending'){
        echo "<a href='admin_pembayaran_update.php?id={$row['id']}&status=dibayar' class='btn-status btn-dibayar'>Dibayar</a>";
        echo "<a href='admin_pembayaran_update.php?id={$row['id']}&status=gagal' class='btn-status btn-gagal'>Gagal</a>";
    }

    echo "<a href='admin_pembayaran_detail.php?id={$row['id']}' class='btn-detail'>Lihat Detail</a>";

    echo "</td></tr>";
}
?>
</table>

<?php include "inc/footer.php"; ?>
