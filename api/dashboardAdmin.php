<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login");
    exit();
}

$total_user    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user WHERE role='user'"))['total'];
$total_admin   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user WHERE role IN ('super_admin', 'admin_user', 'admin_antrean')"))['total'];
$total_antrean = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian"))['total'];
$antrean_hari  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan = DATE(CONVERT_TZ(NOW(), '+00:00', '+07:00'))"))['total'];
$antrean_recent = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BrianHealty - Dashboard Admin</title>
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

    <div class="sidebar admin-theme">
        <div class="sidebar-header"><i class="fas fa-shield-alt brand-icon"></i> Admin Panel</div>
        <div class="sidebar-section">Dashboard</div>
        <ul class="sidebar-menu">
            <li><a href="dashboardAdmin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        </ul>
        
        <div class="sidebar-section">Kelola</div>
        <ul class="sidebar-menu">
            <?php if ($auth['role'] == 'super_admin' || $auth['role'] == 'admin_user') : ?>
                <li><a href="admin/kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
            <?php endif; ?>

            <?php if ($auth['role'] == 'super_admin') : ?>
                <li><a href="admin/kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <?php endif; ?>

            <li><a href="admin/kelola_antrean.php"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
        </ul>

        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="navbar">

            <div class="navbar">
            <!-- Hamburger -->
            <button class="hamburger" onclick="toggleSidebar()">
                <span></span><span></span><span></span>
            </button>
            
            <div class="nav-user">...</div>
            <a href="logout.php" class="btn-logout">...</a>
            </div>

            <div class="nav-user">
                <i class="fas fa-user-shield"></i> 
                <span><?= htmlspecialchars($auth['nama']) ?> (<?= ucfirst(str_replace('_', ' ', $auth['role'])) ?>)</span>
            </div>
            <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <div class="page-header">
                <h2><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                    <div class="stat-info"><p>Total User</p><h2><?= $total_user ?></h2></div>
                </div>
                <?php if ($auth['role'] == 'super_admin') : ?>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info"><p>Total Admin</p><h2><?= $total_admin ?></h2></div>
                </div>
                <?php endif; ?>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-info"><p>Total Antrean</p><h2><?= $total_antrean ?></h2></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-calendar-day"></i></div>
                    <div class="stat-info"><p>Antrean Hari Ini</p><h2><?= $antrean_hari ?></h2></div>
                </div>
            </div>

            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="margin:0;"><i class="fas fa-list-alt"></i> Antrean Terbaru</h3>
                    <a href="admin/kelola_antrean.php" class="btn-edit">Lihat Semua</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>#</th><th>No. Antrean</th><th>Nama Pasien</th><th>Poliklinik</th><th>Tanggal</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($antrean_recent)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong style="color:#1a73e8;"><?= $row['nomor_antrean'] ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td>
                                    <?php 
                                    $badges=['Poli Umum'=>'blue','Poli Gigi'=>'green','Poli Anak'=>'purple','Poli Mata'=>'gray','Poli Jantung'=>'red','Poli Saraf'=>'teal']; 
                                    $cls=$badges[$row['poli']]??'blue'; 
                                    ?>
                                    <span class="badge badge-<?= $cls ?>"><?= $row['poli'] ?></span>
                                </td>
                                <td><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                                <td>
                                    <a href="admin/edit_antrean.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <?php if ($auth['role'] == 'super_admin' || $auth['role'] == 'admin_user') : ?>
                                        <a href="proses/prosesHapusAntrean.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus antrean ini?')"><i class="fas fa-trash"></i> Hapus</a>
                                    <?php endif; ?>
                                </td>
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