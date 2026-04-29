<?php
//Bagian Awal: Memulai session dan melakukan pengecekan keamanan.
session_start();
//Proteksi Halaman: Memastikan hanya user biasa (role='user') yang sudah login yang bisa akses halaman layanan ini.
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Layanan Poli</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="../assets/css/landing.css">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; background: #f0f4f8; }
        .layanan-wrap { padding: 28px; overflow-y: auto; flex: 1; }
        .layanan-wrap section { padding: 0; }
        .layanan-card a { display: inline-block; padding: 9px 22px; background: #1a73e8; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
    <!-- Sidebar User: Menu navigasi untuk user dengan link ke berbagai halaman user. -->
    <div class="sidebar">
        <!-- Sidebar Header: Logo dan nama aplikasi "BrianHealty". -->
        <div class="sidebar-header"><i class="fas fa-hand-holding-medical brand-icon"></i> BrianHealty</div>
        <!-- Sidebar Menu Section: Daftar menu navigasi dengan ikon. -->
        <div class="sidebar-section">Menu</div>
        <!-- Navigation Menu: Link menu dengan layanan.php dimark sebagai active (halaman saat ini). -->
        <ul class="sidebar-menu">
            <li><a href="beranda.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="layanan.php" class="active"><i class="fas fa-hospital"></i> Layanan Poli</a></li>
            <li><a href="pendaftaran.php"><i class="fas fa-clipboard-list"></i> Daftar Antrean</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat Antrean</a></li>
        </ul>
        <!-- Account Section: Bagian akun untuk logout. -->
        <div class="sidebar-section">Akun</div>
        <!-- Logout: Link untuk logout yang mengarah ke halaman utama. -->
        <ul class="sidebar-menu">
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <div class="nav-user"><i class="fas fa-user-circle"></i> Halo, <span><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
            <a href="../index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="layanan-wrap">
            <div class="page-header">
                <h2><i class="fas fa-hospital"></i> Pilih Layanan Poliklinik</h2>
            </div>
            <div class="layanan-grid">
                <div class="layanan-card blue">
                    <div class="layanan-icon"><i class="fas fa-stethoscope"></i></div>
                    <h3>Poli Umum</h3>
                    <p>Layanan kesehatan dasar dan konsultasi dokter umum untuk semua keluhan.</p>
                    <a href="pendaftaran.php?poli=Poli+Umum">Daftar Antrean</a>
                </div>
                <div class="layanan-card green">
                    <div class="layanan-icon"><i class="fas fa-tooth"></i></div>
                    <h3>Poli Gigi</h3>
                    <p>Perawatan kesehatan gigi, gusi, dan estetika mulut bersama dokter gigi terbaik.</p>
                    <a href="pendaftaran.php?poli=Poli+Gigi">Daftar Antrean</a>
                </div>
                <div class="layanan-card purple">
                    <div class="layanan-icon"><i class="fas fa-baby"></i></div>
                    <h3>Poli Anak</h3>
                    <p>Konsultasi tumbuh kembang anak dan spesialis pediatrik berpengalaman.</p>
                    <a href="pendaftaran.php?poli=Poli+Anak">Daftar Antrean</a>
                </div>
                <div class="layanan-card gray">
                    <div class="layanan-icon"><i class="fas fa-eye"></i></div>
                    <h3>Poli Mata</h3>
                    <p>Konsultasi dan pemeriksaan penglihatan dengan teknologi modern.</p>
                    <a href="pendaftaran.php?poli=Poli+Mata">Daftar Antrean</a>
                </div>
                <div class="layanan-card red">
                    <div class="layanan-icon"><i class="fas fa-heartbeat"></i></div>
                    <h3>Poli Jantung</h3>
                    <p>Konsultasi dan terapi kardiovaskular oleh dokter spesialis jantung.</p>
                    <a href="pendaftaran.php?poli=Poli+Jantung">Daftar Antrean</a>
                </div>
                <div class="layanan-card teal">
                    <div class="layanan-icon"><i class="fas fa-brain"></i></div>
                    <h3>Poli Saraf</h3>
                    <p>Diagnosis dan konsultasi sistem saraf dan otak oleh dokter spesialis.</p>
                    <a href="pendaftaran.php?poli=Poli+Saraf">Daftar Antrean</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
