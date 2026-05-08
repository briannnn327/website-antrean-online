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

<!-- ✅ OVERLAY (klik untuk tutup sidebar di mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

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
        <!-- ✅ HAMBURGER BUTTON -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
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

        <!-- Tabel Data BPS -->
        <div class="card" style="margin-top: 20px;">
            <h3><i class="fas fa-chart-line"></i> Persentase Keluhan Kesehatan (BPS 2017)</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th>No</th>
                            <th>Provinsi</th>
                            <th class="text-right">Persentase Keluhan (%)</th>
                        </tr>
                    </thead>
                    <tbody id="data-api-body">
                        <tr>
                            <td colspan="3" style="text-align:center;">Memproses data dari BPS...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// ✅ HAMBURGER TOGGLE
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

// ✅ FETCH DATA BPS (Struktur SIMDASI Modern)
async function fetchBPS() {
    const body = document.getElementById('data-api-body');
    
    try {
        const response = await fetch("https://webapi.bps.go.id/v1/api/interoperabilitas/datasource/simdasi/id/25/tahun/2017/id_tabel/TEptbDV0QlRORVl6cjl0THhMbk02Zz09/wilayah/0000000/key/e34d50c3e2e4773ebe4c8162f7a76057");

        if (!response.ok) throw new Error("Gagal mengambil data. Status: " + response.status);

        const res = await response.json();
        
        // Data utama berada di res.data[1].data
        if (res.status === "OK" && res.data[1] && res.data[1].data) {
            const listProvinsi = res.data[1].data;
            let baris = '';

            listProvinsi.forEach((item, index) => {
                // Ambil nilai dari object variables (key unik: lxkwts7rnj)
                // Kita gunakan Object.values()[0] agar dinamis jika key berubah
                const valObj = Object.values(item.variables)[0];
                const nilai = valObj ? valObj.value : "0";

                baris += `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="font-bold">${item.label}</td>
                        <td class="text-right">${nilai}%</td>
                    </tr>
                `;
            });

            body.innerHTML = baris;
        } else {
            throw new Error("Format data tidak dikenali atau data kosong.");
        }

    } catch (err) {
        console.error("Detail Error:", err);
        body.innerHTML = `<tr><td colspan="3" style="text-align:center; color:red;">
            <strong>Error:</strong><br>${err.message}
        </td></tr>`;
    }
}

document.addEventListener('DOMContentLoaded', fetchBPS);
</script>

</body>
</html>