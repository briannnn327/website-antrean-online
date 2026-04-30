<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login");
    exit();
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
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <style>
        .layanan-wrap { padding: 20px; overflow-y: auto; flex: 1; }
        .layanan-wrap section { padding: 0; }
        .layanan-card a {
            display: inline-block;
            padding: 9px 22px;
            background: #1a73e8;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .layanan-wrap { padding: 14px; }
            .layanan-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
            .layanan-card { padding: 16px; }
        }
        @media (max-width: 480px) {
            .layanan-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ✅ OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header"><i class="fas fa-hand-holding-medical brand-icon"></i> BrianHealty</div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-menu">
        <li><a href="beranda.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="layanan.php" class="active"><i class="fas fa-hospital"></i> Layanan Poli</a></li>
        <li><a href="pendaftaran.php"><i class="fas fa-clipboard-list"></i> Daftar Antrean</a></li>
        <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat Antrean</a></li>
    </ul>
    <div class="sidebar-section">Akun</div>
    <ul class="sidebar-menu">
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="navbar">
        <!-- ✅ HAMBURGER -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="nav-user"><i class="fas fa-user-circle"></i> Halo, <span><?= htmlspecialchars($auth['nama']) ?></span></div>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
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

<script>
const hamburgerBtn   = document.getElementById('hamburgerBtn');
const sidebar        = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function openSidebar() {
    sidebar.classList.add('open');
    sidebarOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

hamburgerBtn.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
});
sidebarOverlay.addEventListener('click', closeSidebar);

document.querySelectorAll('.sidebar-menu li a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) closeSidebar();
    });
});
</script>

</body>
</html>