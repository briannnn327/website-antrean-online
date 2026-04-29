<?php
ini_set('session.save_path', '/tmp');  // ← tambah
session_start();                        // ← tambah
ob_start();
require_once __DIR__ . '/../service/koneksi.php';
require_once __DIR__ . '/../service/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!$koneksi) {
        setcookie('flash_error', 'Koneksi database terputus!', time()+10, '/', '', true, false);
        header("Location: ../login.php");  // ← fix path
        exit();
    }

    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: ../register.php"); exit();  // ← fix path
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql  = "INSERT INTO user (nama, email, password, role) VALUES ('$nama', '$email', '$hash', 'user')";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: ../login.php");  // ← fix path
    } else {
        $_SESSION['error'] = "Terjadi kesalahan sistem.";
        header("Location: ../register.php");  // ← fix path
    }
    exit();
}