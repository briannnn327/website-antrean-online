<?php
ob_start();
require __DIR__ . '/../service/koneksi.php';
require __DIR__ . '/../service/auth.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $redirect = ($role === 'user') ? 'tambah_user' : 'tambah_admin';
        setcookie('flash_error', 'Email sudah digunakan!', time()+10, '/', '', true, false);
        header("Location: ../admin/$redirect.php"); exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql  = "INSERT INTO user (nama, email, password, role) VALUES ('$nama', '$email', '$hash', '$role')";

    if (mysqli_query($koneksi, $sql)) {
        $redirect = $role === 'user' ? 'kelola_user' : 'kelola_admin';
        setcookie('flash_success', "Data $role berhasil ditambahkan!", time()+10, '/', '', true, false);
        header("Location: ../admin/$redirect.php");
    } else {
        $redirect = $role === 'user' ? 'tambah_user' : 'tambah_admin';
        setcookie('flash_error', 'Gagal menyimpan data.', time()+10, '/', '', true, false);
        header("Location: ../admin/$redirect.php");
    }
    exit();
}
ob_end_flush();