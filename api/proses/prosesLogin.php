<?php
// 1. Inisialisasi Output Buffering & Session di baris paling atas
ob_start();
session_start();

// 2. Hubungkan ke database menggunakan __DIR__ untuk path absolut
require_once __DIR__ . '/../service/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Pastikan koneksi database tersedia sebelum lanjut
    if (!$koneksi) {
        $_SESSION['error'] = "Koneksi database terputus!";
        session_write_close();
        header("Location: /login"); // Gunakan rute yang didefinisikan di vercel.json
        exit();
    }

    // Membersihkan input dari potensi SQL Injection
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Query mencari user berdasarkan email
    $query  = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");

    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan data ke session
            $_SESSION['id']   = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            // 4. Tutup penulisan session sebelum redirect (SANGAT PENTING di Vercel)
            session_write_close();

            // 5. Redirect menggunakan rute sesuai vercel.json
            if (in_array($row['role'], ['super_admin', 'admin_user', 'admin_antrean'])) {
                header("Location: /dashboardAdmin"); 
            } else {
                header("Location: /beranda");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            session_write_close();
            header("Location: /login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
        session_write_close();
        header("Location: /login");
        exit();
    }
}

// Mengakhiri output buffering
ob_end_flush();