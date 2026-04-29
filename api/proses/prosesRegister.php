<?php
// Bagian Awal: Memulai session dan memanggil file koneksi database.
session_start();
require '../service/koneksi.php';

// Pengecekan Metode: Memastikan form dikirim dengan metode POST (bukan GET atau yang lain).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil data dari form POST dan membersihkannya dengan htmlspecialchars() untuk keamanan (mencegah XSS).
    $nama     = htmlspecialchars($_POST['nama']);         // Nama user dari form register
    $email    = htmlspecialchars($_POST['email']);       // Email user dari form register
    $password = $_POST['password'];                       // Password dalam plain text (akan di-hash kemudian)

    // Validasi Email Unik: Mengecek apakah email sudah terdaftar di database. Jika sudah ada, tampilkan error dan kembali ke form.
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: ../register.php"); exit();     // Redirect kembali ke form register dengan pesan error
    }

    // Hashing Password: Mengenkripsi password menggunakan password_hash() dengan algoritma default (bcrypt) untuk keamanan.
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Query Insert: Menyiapkan perintah SQL untuk memasukkan data user baru ke tabel 'user' dengan role default 'user'.
    $sql  = "INSERT INTO user (nama, email, password, role) VALUES ('$nama', '$email', '$hash', 'user')";

    // Eksekusi Query: Menjalankan query insert. Jika berhasil, set pesan success dan redirect ke login. Jika gagal, set error dan kembali ke form.
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: ../login.php");                // Redirect ke halaman login setelah registrasi sukses
    } else {
        $_SESSION['error'] = "Terjadi kesalahan sistem.";  // Pesan error jika database error
        header("Location: ../register.php");             // Redirect kembali ke form register
    }
    exit();
}
?>
