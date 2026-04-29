<?php
// 1. Output Buffering untuk mencegah error "Headers already sent"
ob_start();
session_start();

// 2. Gunakan path yang lebih aman
require_once __DIR__ . '/../service/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Membersihkan input
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query  = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['id']   = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            // 3. PERBAIKAN PATH REDIRECT
            // Di Vercel, arahkan langsung ke root (/) atau folder yang sesuai dari domain utama
            if (in_array($row['role'], ['super_admin', 'admin_user', 'admin_antrean'])) {
                header("Location: /dashboardAdmin.php");
            } else {
                header("Location: /beranda.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: /login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
        header("Location: /login.php");
        exit();
    }
}

// 4. Mengakhiri output buffering
ob_end_flush();