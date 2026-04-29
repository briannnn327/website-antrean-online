<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login");
    exit();
}

//Bagian Query: Mengambil semua data dari tabel 'antrian' dan mengurutkannya dari yang terbaru (ID DESC) untuk ditampilkan di tabel riwayat.
$antrean = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<!-- Bagian Head: Pengaturan judul halaman, font Poppins, icon FontAwesome, dan memanggil file CSS khusus dashboard (app.css). -->
<head>
    <meta charset="UTF-8">
    <title>BrianHealty - Riwayat Antrean</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

<!-- Overlay -->
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- Sidebar seperti biasa -->
<div class="sidebar" id="sidebar">
  ...
</div>

    <!-- Sidebar: Menu navigasi samping untuk memudahkan user berpindah antar fitur seperti Dashboard, Layanan, dan Riwayat. -->
    <div class="sidebar">
        <div class="sidebar-header"><i class="fas fa-hand-holding-medical brand-icon"></i> BrianHealty</div>
        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-menu">
            <li><a href="beranda.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="layanan.php"><i class="fas fa-hospital"></i> Layanan Poli</a></li>
            <li><a href="pendaftaran.php"><i class="fas fa-clipboard-list"></i> Daftar Antrean</a></li>
            <li><a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Navbar Atas: Menampilkan nama user yang sedang login menggunakan session dan menyediakan tombol logout. -->
        <div class="navbar">
            <div class="navbar">
                <!-- Hamburger -->
                <button class="hamburger" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                
                <div class="nav-user">...</div>
                <a href="logout.php" class="btn-logout">...</a>
            </div>

            <div class="nav-user"><i class="fas fa-user-circle"></i> Halo, <span><?= htmlspecialchars($auth['nama']) ?></span></div>
            <a href="../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="content">
            <!-- Page Header: Judul halaman riwayat dan tombol pintas "Daftar Baru" jika user ingin membuat antrean lagi. -->
            <div class="page-header">
                <h2><i class="fas fa-history"></i> Riwayat Semua Antrean</h2>
                <a href="pendaftaran.php" class="btn-add"><i class="fas fa-plus"></i> Daftar Baru</a>
            </div>

            <!-- Card Riwayat: Wadah utama berbentuk kartu yang berisi tabel data antrean -->
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>#</th><th>No. Antrean</th><th>Nama Pasien</th><th>NIK</th><th>Poliklinik</th><th>Tanggal Kunjungan</th></tr>
                        </thead>
                        <tbody>
                        <!-- PHP Loop: Mengulangi data hasil query tadi. Setiap baris database akan dicetak menjadi baris tabel (row). -->
                        <?php $no=1; while($row = mysqli_fetch_assoc($antrean)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong style="color:#1a73e8;"><?= $row['nomor_antrean'] ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= $row['nik'] ?></td>
                                <!-- Badge Logika: Memberikan warna label (badge) yang berbeda-beda secara otomatis tergantung jenis poliklinik yang dipilih. -->
                                <td>
                                    <?php 
                                    $badges=['Poli Umum'=>'blue','Poli Gigi'=>'green','Poli Anak'=>'purple','Poli Mata'=>'gray','Poli Jantung'=>'red','Poli Saraf'=>'teal']; 
                                    $cls=$badges[$row['poli']]??'blue'; 
                                    ?>
                                    <span class="badge badge-<?= $cls ?>"><?= $row['poli'] ?></span>
                                </td>
                                <!-- Format Tanggal: Mengubah format tanggal dari database (Y-m-d). -->
                                <td><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('show');
    }
</script>
</body>
</html>