<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login");
    exit();
}

$antrean = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Riwayat Antrean</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<!-- ✅ OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
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

    <div class="content">
        <div class="page-header">
            <h2><i class="fas fa-history"></i> Riwayat Semua Antrean</h2>
            <a href="pendaftaran.php" class="btn-add"><i class="fas fa-plus"></i> Daftar Baru</a>
        </div>

        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Antrean</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Poliklinik</th>
                            <th>Tanggal Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($antrean)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong style="color:#1a73e8;"><?= $row['nomor_antrean'] ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                            <td><?= $row['nik'] ?></td>
                            <td>
                                <?php
                                $badges = ['Poli Umum'=>'blue','Poli Gigi'=>'green','Poli Anak'=>'purple','Poli Mata'=>'gray','Poli Jantung'=>'red','Poli Saraf'=>'teal'];
                                $cls = $badges[$row['poli']] ?? 'blue';
                                ?>
                                <span class="badge badge-<?= $cls ?>"><?= $row['poli'] ?></span>
                            </td>
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