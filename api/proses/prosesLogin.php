<?php
ini_set('session.save_path', '/tmp');
session_start();  // ← tambah
ob_start();
require_once __DIR__ . '/../service/koneksi.php';
require_once __DIR__ . '/../service/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$koneksi) {
        $_SESSION['error'] = 'Koneksi database terputus!';  // ← ganti pakai session
        header("Location: ../login.php");
        exit();
    }

    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $query    = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($password, $row['password'])) {
            set_auth_cookie([
                'id'   => $row['id'],
                'nama' => $row['nama'],
                'role' => $row['role'],
            ]);

            if (in_array($row['role'], ['super_admin', 'admin_user', 'admin_antrean'])) {
                header("Location: /dashboardAdmin");
            } else {
                header("Location: /beranda");
            }
            exit();
        } else {
            $_SESSION['error'] = 'Password salah!';  // ← ganti pakai session
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Email tidak ditemukan!';  // ← ganti pakai session
        header("Location: ../login.php");
        exit();
    }
}
ob_end_flush();