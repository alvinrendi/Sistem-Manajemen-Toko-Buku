<?php
session_start();
include "config/koneksi.php"; // koneksi database

// --- Ambil Data Buku dengan Harga Terbaru ---
$query = "
    SELECT b.id, b.judul, b.penulis, b.stok,
           h.harga_akhir AS harga_terbaru, h.tanggal_update
    FROM buku b
    LEFT JOIN (
        SELECT hh.*
        FROM harga hh
        INNER JOIN (
            SELECT buku_id, MAX(tanggal_update) AS max_date
            FROM harga
            GROUP BY buku_id
        ) last_harga 
        ON hh.buku_id = last_harga.buku_id 
        AND hh.tanggal_update = last_harga.max_date
    ) h ON b.id = h.buku_id
";




$buku = $koneksi->query($query);
if (!$buku) {
    die("Query error: " . $koneksi->error);
}

// --- Update Harga Buku ---
if (isset($_POST['update_harga'])) {
    $buku_id   = (int) $_POST['buku_id'];
    $harga_baru = (int) $_POST['harga_baru'];

    // Cari harga terakhir
    $stmt_last = $koneksi->prepare("
        SELECT harga_akhir 
        FROM harga 
        WHERE buku_id = ? 
        ORDER BY tanggal_update DESC 
        LIMIT 1
    ");
    $stmt_last->bind_param("i", $buku_id);
    $stmt_last->execute();
    $result_last = $stmt_last->get_result();

    $harga_awal = ($result_last->num_rows > 0) ? $result_last->fetch_assoc()['harga_akhir'] : 0;

    // Insert harga baru
    $stmt_insert = $koneksi->prepare("
        INSERT INTO harga (buku_id, harga_awal, harga_akhir) 
        VALUES (?, ?, ?)
    ");
    $stmt_insert->bind_param("iii", $buku_id, $harga_awal, $harga_baru);
    $stmt_insert->execute();

    header("Location: admin_harga.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Manajemen Harga Buku</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; margin: 0; }
        header {
            background: linear-gradient(120deg, #1e3c72, #2a5298);
            color: white; padding: 20px; text-align: center;
        }
        .container { max-width: 1000px; margin: auto; padding: 25px; }
        h2 { color: #1e3c72; margin-bottom: 20px; }
        table { 
            width: 100%; border-collapse: collapse; 
            background: white; border-radius: 12px; 
            overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.1); 
        }
        th, td { padding: 14px; text-align: left; }
        th { background: #1e3c72; color: white; }
        tr:nth-child(even) { background: #f8fafc; }
        form { display: flex; gap: 10px; }
        input[type=number] { 
            padding: 8px; border: 1px solid #ccc; 
            border-radius: 6px; width: 120px; 
        }
        button {
            padding: 10px 16px; background: #1e3c72; 
            color: white; border: none; border-radius: 6px; 
            cursor: pointer; transition: 0.3s;
        }
        button:hover { background: #16345a; }
    </style>
</head>
<body>
<header>
    <h1>📚 Admin Dashboard - Manajemen Harga Buku</h1>
</header>
<div class="container">
    <h2>Daftar Buku & Harga</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Stok</th>
            <th>Harga Terbaru</th>
            <th>Tanggal Update</th>
            <th>Update Harga</th>
        </tr>
        <?php while ($row = $buku->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['penulis']) ?></td>
            <td><?= $row['stok'] ?></td>
            <td>
                Rp <?= $row['harga_terbaru'] 
                        ? number_format($row['harga_terbaru'], 0, ',', '.') 
                        : '-' ?>
            </td>
            <td><?= $row['tanggal_update'] ?: '-' ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="buku_id" value="<?= $row['id'] ?>">
                    <input type="number" name="harga_baru" placeholder="Harga baru" required>
                    <button type="submit" name="update_harga">Update</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
