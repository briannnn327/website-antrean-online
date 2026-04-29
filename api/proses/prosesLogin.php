<?php
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
require __DIR__ . '/../service/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Gunakan prepared statement agar aman dari SQL Injection
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['id']   = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'super_admin' || $row['role'] == 'admin_user' || $row['role'] == 'admin_antrean') {
                // Redirect ke dashboard admin
                header("Location: /api/dashboardAdmin.php");
            } else {
                // Redirect ke dashboard user biasa
                header("Location: /api/beranda.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            // Redirect kembali ke login page
            header("Location: /api/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
        // Redirect kembali ke login page
        header("Location: /api/login.php");
        exit();
    }
}