<?php
include "config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']); // admin atau user

    // Cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "⚠️ Username sudah terdaftar!";
    } elseif ($password !== $confirm_password) {
        $error = "⚠️ Password dan Konfirmasi Password tidak sama!";
    } else {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = mysqli_query($koneksi, "INSERT INTO user (username, password, role) VALUES ('$username', '$hashed_password', '$role')");
        if ($query) {
            header("Location: login.php?success=1");
            exit;
        } else {
            $error = "⚠️ Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Toko Buku</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #2193b0, #6dd5ed);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: #fff;
            padding: 40px 30px;
            width: 100%;
            max-width: 420px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0d47a1;
        }
        .form-group { margin-bottom: 18px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #0d47a1;
        }
        .register-box button {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #0d47a1;
            color: white;
            transition: 0.3s;
        }
        .btn-primary:hover { background-color: #08306b; }
        .btn-secondary {
            background-color: #ccc;
            color: #333;
        }
        .btn-secondary:hover { background-color: #aaa; }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .register-box a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #0d47a1;
            font-size: 14px;
            text-decoration: none;
        }
        .register-box a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="register-box">
    <h2>Daftar Akun</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Masukkan Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Masukkan Password" required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        </div>
        <div class="form-group">
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin / Penjual</option>
                <option value="user">User / Pembeli</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Daftar</button>
        <button type="button" class="btn-secondary" onclick="window.location.href='login.php'">Batal</button>
    </form>
    <a href="login.php">Sudah punya akun? Login di sini</a>
</div>
</body>
</html>
