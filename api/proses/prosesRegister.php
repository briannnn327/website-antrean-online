<?php
// Bagian Awal: Memulai session dan memanggil file koneksi database.
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
require __DIR__ . '/../service/koneksi.php';

// Pengecekan Metode: Memastikan form dikirim dengan metode POST (bukan GET atau yang lain).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil data dari form POST dan membersihkannya dengan htmlspecialchars() untuk keamanan (mencegah XSS).
    $nama     = htmlspecialchars(trim($_POST['nama']));         // Nama user dari form register
    $email    = htmlspecialchars(trim($_POST['email']));       // Email user dari form register
    $password = $_POST['password'];                       // Password dalam plain text (akan di-hash kemudian)

    // Validasi Email Unik: Menggunakan prepared statement untuk keamanan (mencegah SQL Injection).
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: /api/register.php"); 
        exit();     
    }

    // Hashing Password: Mengenkripsi password menggunakan password_hash() dengan algoritma default (bcrypt) untuk keamanan.
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Query Insert menggunakan prepared statement untuk keamanan.
    $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO user (nama, email, password, role) VALUES (?, ?, ?, 'user')");
    mysqli_stmt_bind_param($stmt_insert, "sss", $nama, $email, $hash);

    // Eksekusi Query: Jika berhasil, set pesan success dan redirect ke login. Jika gagal, set error dan kembali ke form.
    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: /api/login.php");                
    } else {
        $_SESSION['error'] = "Terjadi kesalahan sistem.";  
        header("Location: /api/register.php");             
    }
    exit();
}
?>
