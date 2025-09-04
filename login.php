<?php
session_start();
include "config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input user
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cari user berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Verifikasi password
        if (password_verify($password, $data['password'])) {
            // Simpan session
            $_SESSION['user_id']  = $data['id']; 
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            // Cek role → redirect ke dashboard sesuai peran
            if ($data['role'] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Buku</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d47a1, #1976d2, #42a5f5);
            background-size: 300% 300%;
            animation: gradientBG 12s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-box {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            border-radius: 14px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #0d47a1;
            font-size: 28px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            font-size: 16px;
            border: 1px solid #bbb;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s;
        }
        .form-group input:focus {
            border-color: #1976d2;
            box-shadow: 0 0 6px rgba(25, 118, 210, 0.4);
        }
        .btn-group { display: flex; gap: 10px; }
        .btn {
            flex: 1;
            padding: 14px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login { background: #0d47a1; color: white; }
        .btn-login:hover { background: #08306b; }
        .btn-batal { background: #ccc; color: #333; }
        .btn-batal:hover { background: #999; color: white; }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .login-box a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #0d47a1;
            text-decoration: none;
            font-size: 14px;
        }
        .login-box a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login Toko Buku</h2>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Masukkan Username Anda" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Masukkan Password Anda" required>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-login">Login</button>
            <button type="reset" class="btn btn-batal">Batal</button>
        </div>
    </form>

    <a href="register.php">Belum punya akun? Daftar di sini</a>
</div>
</body>
</html>
