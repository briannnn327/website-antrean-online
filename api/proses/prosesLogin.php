<?php
ob_start();
require_once __DIR__ . '/../service/koneksi.php';
require_once __DIR__ . '/../service/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$koneksi) {
        setcookie('flash_error', 'Koneksi database terputus!', time()+10, '/', '', true, false);
        header("Location: /login");
        exit();
    }

    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $query    = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($password, $row['password'])) {
            // Simpan ke cookie JWT, bukan session
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
            setcookie('flash_error', 'Password salah!', time()+10, '/', '', true, false);
            header("Location: /login");
            exit();
        }
    } else {
        setcookie('flash_error', 'Email tidak ditemukan!', time()+10, '/', '', true, false);
        header("Location: /login");
        exit();
    }
}
ob_end_flush();