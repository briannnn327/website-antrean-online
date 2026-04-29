<?php
session_start();
require __DIR__ . '/service/koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$nama_user        = $_SESSION['nama'];
$antrean          = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC LIMIT 10");
$total_antrean    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian"))['total'];
$antrean_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan = CURDATE()"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Dashboard User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <style>
        .text-right { text-align: right; padding-right: 15px !important; }
        .font-bold { font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-hand-holding-medical brand-icon"></i> BrianHealty
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-menu">
        <li><a href="beranda.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="layanan.php"><i class="fas fa-hospital"></i> Layanan Poli</a></li>
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
        <div class="nav-user">
            <i class="fas fa-user-circle"></i> Halo, 
            <span><?= htmlspecialchars($nama_user) ?></span>
        </div>
        <a href="logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="content">
        <div class="page-header">
            <h2><i class="fas fa-home"></i> Dashboard Pasien</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-info">
                    <p>Total Antrean</p>
                    <h2><?= $total_antrean ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <p>Antrean Hari Ini</p>
                    <h2><?= $antrean_hari_ini ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-hospital"></i></div>
                <div class="stat-info">
                    <p>Poliklinik</p>
                    <h2>6</h2>
                </div>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-list-alt"></i> 10 Antrean Terbaru</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Antrean</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Poliklinik</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($antrean)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong style="color:#1a73e8;"><?= $row['nomor_antrean'] ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                            <td><?= $row['nik'] ?></td>
                            <td><span class="badge"><?= $row['poli'] ?></span></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3><i class="fas fa-chart-line"></i> Data Fasilitas Kesehatan (BPS 2018)</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th>Kab/Kota</th>
                            <th class="text-right">Rumah Sakit</th>
                            <th class="text-right">Puskesmas</th>
                            <th class="text-right">Posyandu</th>
                            <th class="text-right">Polindes</th>
                        </tr>
                    </thead>
                    <tbody id="data-api-body">
                        <tr>
                            <td colspan="5" style="text-align:center;">Memproses data dari BPS...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
async function fetchBPS() {
    const body = document.getElementById('data-api-body');
    try {
        const response = await fetch('./api_kesehatan.php?v=' + Date.now());
        const res = await response.json();
        const wilayah = res.vervar;
        const dataVal = res.datacontent;
        let baris = '';

        wilayah.forEach(w => {
            const idWil = w.val;
            let rs = 0, pusk = 0, posy = 0, polin = 0;

            Object.keys(dataVal).forEach(key => {
                if (key.includes("206") && key.includes(idWil)) {
                    if (key.includes("178")) rs = dataVal[key];
                    if (key.includes("179")) pusk = dataVal[key];
                    if (key.includes("180")) posy = dataVal[key];
                    if (key.includes("181")) polin = dataVal[key];
                }
            });
            const format = (v) => Number(v || 0).toLocaleString('id-ID');
            baris += `<tr><td class="font-bold">${w.label}</td><td class="text-right">${format(rs)}</td><td class="text-right">${format(pusk)}</td><td class="text-right">${format(posy)}</td><td class="text-right">${format(polin)}</td></tr>`;
        });
        body.innerHTML = baris;
    } catch (err) {
        body.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">${err.message}</td></tr>`;
    }
}
document.addEventListener('DOMContentLoaded', fetchBPS);
</script>

</body>
</html>