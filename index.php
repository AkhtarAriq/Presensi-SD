<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi SD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            margin: 0 5px;
        }
        a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 14px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Sistem Presensi SD</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Anda sudah login.</p>
            <a href="presensi.php" class="button">Masuk ke Halaman Presensi</a>
            <a href="register.php" class="button">Register</a>
            <a href="logout.php" class="button">Logout</a>
        <?php else: ?>
            <p>Silakan login atau daftar:</p>
            <a href="login.php" class="button">Login</a>
            <a href="register_guru.php" class="button">Register Guru</a>
        <?php endif; ?>
    </div>
</body>
</html>
