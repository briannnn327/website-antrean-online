<?php
// Bagian Awal: Memulai session dan memanggil file koneksi database.
session_start();
// Path Koneksi: Dari folder 'proses', naik 1 tingkat (../) untuk masuk ke folder 'api', kemudian ke folder 'service'.
require '../service/koneksi.php'; 

// Pengecekan Metode: Memastikan form dikirim dengan metode POST dari halaman login.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil email dan password dari form login dan membersihkannya dengan htmlspecialchars().
    $email    = htmlspecialchars($_POST['email']);      // Email user dari form login
    $password = $_POST['password'];                     // Password dalam plain text (akan diverifikasi dengan hash)

    // Query User: Mencari user dengan email yang sesuai di database.
    $query  = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

    // Pengecekan User Exists: Jika user dengan email tersebut ditemukan (hasil query = 1 baris).
    if (mysqli_num_rows($query) === 1) {
        // Fetch User Data: Mengambil data user dari hasil query ke dalam array.
        $row = mysqli_fetch_assoc($query);
        // Verifikasi Password: Mengecek apakah password yang diinput sesuai dengan hash di database menggunakan password_verify().
        if (password_verify($password, $row['password'])) {
            // Set Session Data: Menyimpan data user (id, nama, role) ke dalam session untuk digunakan di halaman lain.
            $_SESSION['id']   = $row['id'];             // ID user untuk identifikasi
            $_SESSION['nama'] = $row['nama'];           // Nama user untuk ditampilkan
            $_SESSION['role'] = $row['role'];           // Role user (user, super_admin, admin_user, atau admin_antrean)

            // Pengecekan Role & Redirect: Jika user adalah admin (super_admin, admin_user, admin_antrean), redirect ke dashboard admin. Jika user biasa, redirect ke beranda.
            if ($row['role'] == 'super_admin' || $row['role'] == 'admin_user' || $row['role'] == 'admin_antrean') {
                // Admin Dashboard: Redirect ke halaman dashboard admin.
                header("Location: ../dashboardAdmin.php");
            } else {
                // User Dashboard: Redirect ke halaman beranda user biasa.
                header("Location: ../beranda.php");
            }
            exit();
        } else {
            // Error Password: Password yang diinput tidak sesuai dengan yang tersimpan di database.
            $_SESSION['error'] = "Password salah!";
            header("Location: ../login.php"); 
            exit();
        }
    } else {
        // Error Email Not Found: Email yang diinput tidak ditemukan di database.
        $_SESSION['error'] = "Email tidak ditemukan!";
        header("Location: ../login.php"); 
        exit();
    }
}
?>