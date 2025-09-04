<?php 
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['username'])) header("Location: login.php");

if (isset($_POST['simpan'])) {
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $harga   = $_POST['harga'];
    $stok    = $_POST['stok'];

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    $folder = "assets/img/";

    if ($gambar != "") {
        $ext = pathinfo($gambar, PATHINFO_EXTENSION);
        $fileName = time().".".$ext;
        move_uploaded_file($tmp, $folder.$fileName);
    } else {
        $fileName = "default.png";
    }

    $sql = "INSERT INTO buku (judul, penulis, harga, stok, gambar) VALUES 
            ('$judul', '$penulis', '$harga', '$stok', '$fileName')";
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Buku berhasil ditambahkan!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan buku!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #0d1b4c; /* biru tua */
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 650px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }

        .form-header {
            background: linear-gradient(135deg, #0033cc, #0052cc);
            color: white;
            text-align: center;
            padding: 18px;
            border-radius: 12px;
            margin: -40px -40px 30px -40px;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .form-header span {
            font-size: 26px;
            margin-right: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 13px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 15px;
            transition: 0.3s;
            background: #fdfdfd;
        }

        input:focus {
            border-color: #0033cc;
            box-shadow: 0 0 6px rgba(0,51,204,0.3);
        }

        .preview {
            margin-top: 12px;
            text-align: center;
        }

        .preview img {
            width: 120px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            font-size: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0072ff, #0052cc);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0052cc, #0072ff);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,114,255,0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #b00020, #e53935);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #e53935, #b00020);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(229,57,53,0.3);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-header"><span>📚</span> Tambah Buku Baru</div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="judul">Judul Buku</label>
            <input type="text" name="judul" id="judul" required>
        </div>
        <div class="form-group">
            <label for="penulis">Penulis</label>
            <input type="text" name="penulis" id="penulis" required>
        </div>
        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" required>
        </div>
        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" name="stok" id="stok" required>
        </div>
        <div class="form-group">
            <label for="gambar">Upload Gambar</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" onchange="previewImage(event)">
            <div class="preview" id="preview"></div>
        </div>
        <div class="btn-container">
            <button type="submit" name="simpan" class="btn btn-primary">✅ Simpan</button>
            <a href="admin_dashboard.php" class="btn btn-danger">❌ Batal</a>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        var preview = document.getElementById('preview');
        preview.innerHTML = "";
        var img = document.createElement("img");
        img.src = URL.createObjectURL(event.target.files[0]);
        preview.appendChild(img);
    }
</script>

</body>
</html>
