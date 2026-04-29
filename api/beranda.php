<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login");
    exit();
}

$nama_user        = $auth['nama'];
$antrean          = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC LIMIT 10");
$total_antrean    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian"))['total'];
$antrean_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan = DATE(CONVERT_TZ(NOW(), '+00:00', '+07:00'))"))['total'];
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

<!-- Overlay untuk menutup sidebar di mobile -->
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
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
        <button class="hamburger" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </button>
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
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}

async function fetchBPS() {
    const body = document.getElementById('data-api-body');
    body.innerHTML = '<tr><td colspan="5" style="text-align:center;">Memproses data dari BPS...</td></tr>';
    try {
        const response = await fetch('https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/3500/var/206/th/118/key/e34d50c3e2e4773ebe4c8162f7a76057');
        if (!response.ok) throw new Error("API tidak merespon. Status: " + response.status);
        const res = await response.json();
        const wilayah = res.vervar;
        const dataVal = res.datacontent;
        const allKeys = Object.keys(dataVal);
        let baris = '';
        wilayah.forEach(w => {
            const idWil = String(w.val);
            const findKey = (kodeTur) => allKeys.find(k =>
                k.startsWith(idWil) && k.includes("206") && k.includes(kodeTur)
            );
            const getVal = (key) => {
                const v = dataVal[key];
                if (!v || v === "-") return "0";
                return Number(v).toLocaleString('id-ID');
            };
            baris += `
                <tr>
                    <td class="font-bold">${w.label}</td>
                    <td class="text-right">${getVal(findKey("178"))}</td>
                    <td class="text-right">${getVal(findKey("179"))}</td>
                    <td class="text-right">${getVal(findKey("180"))}</td>
                    <td class="text-right">${getVal(findKey("181"))}</td>
                </tr>
            `;
        });
        body.innerHTML = baris;
    } catch (err) {
        console.error(err);
        body.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;"><strong>Error:</strong><br>${err.message}</td></tr>`;
    }
}
document.addEventListener('DOMContentLoaded', fetchBPS);
</script>

</body>
</html>