<?php
require_once __DIR__ . '/../service/auth.php';
require_once __DIR__ . '/../service/koneksi.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login");
    exit();
}
// Pengambilan Data Antrean: Mengambil ID antrean dari URL parameter GET dan fetch data dari database.
$id = intval($_GET['id']);
$q = mysqli_query($koneksi, "SELECT * FROM antrian WHERE id='$id'");
$data = mysqli_fetch_assoc($q);
// Validasi Data Exists: Jika data antrean tidak ditemukan, tampilkan error dan hentikan eksekusi.
if (!$data) { die("Data antrean tidak ditemukan!"); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edit Antrean</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
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
            <li><a href="../dashboardAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        </ul>
        <div class="sidebar-section">Kelola</div>
        <ul class="sidebar-menu">
            <?php if ($auth['role'] == 'super_admin' || $auth['role'] == 'admin_user') : ?>
                <li><a href="kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
            <?php endif; ?>
            <?php if ($auth['role'] == 'super_admin') : ?>
                <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <?php endif; ?>
            <li><a href="kelola_antrean.php" class="active"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
        <!-- Hamburger -->
        <button class="hamburger" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </button>
        
        <div class="nav-user">...</div>
        <a href="logout.php" class="btn-logout">...</a>
        </div>
            <div class="nav-user"><i class="fas fa-user-shield"></i> Admin: <span><?= htmlspecialchars($auth['nama']) ?></span></div>
            <a href="../../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <div class="page-header">
                <h2><i class="fas fa-edit"></i> Edit Antrean</h2>
            </div>
            <div class="card" style="max-width:620px;">
                <h3>Form Edit Antrean</h3>
                <!-- Form Edit Antrean: Formulir untuk mengubah data antrean yang sudah ada (nama, NIK, poli, tanggal, nomor). -->
                <form action="../proses/prosesEditAntrean.php" method="POST">
                    <!-- Hidden Input ID: Menyimpan ID antrean yang sedang diubah untuk digunakan oleh proses update. -->
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <!-- Nama Pasien Input: Kolom teks untuk mengubah nama pasien yang sudah terisi dengan data lama. -->
                    <div class="form-group">
                        <label>Nama Lengkap Pasien</label>
                        <input type="text" name="nama_pasien" required value="<?= htmlspecialchars($data['nama_pasien']) ?>">
                    </div>
                    <!-- NIK Input: Kolom teks untuk mengubah NIK pasien (max 16 digit). -->
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" required maxlength="16" value="<?= $data['nik'] ?>">
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Poliklinik</label>
                    <!-- Poli Dropdown: Select untuk mengubah poliklinik (dengan pre-selected sesuai data lama). -->
                            <select name="poli" required>
                                <!-- PHP Loop Generate Options: Membuat option dropdown untuk semua jenis poli, dengan selected sesuai poli yang sudah terdaftar. -->
                                <?php
                                $polis = ['Poli Umum','Poli Gigi','Poli Anak','Poli Mata','Poli Jantung','Poli Saraf'];
                                foreach($polis as $p) {
                                    $sel = $data['poli']==$p ? 'selected' : '';
                                    echo "<option value='$p' $sel>$p</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kunjungan</label>
                            <input type="date" name="tanggal" required value="<?= $data['tanggal_kunjungan'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor Antrean</label>
                        <input type="text" name="nomor_antrean" required value="<?= $data['nomor_antrean'] ?>">
                    </div>
                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update</button>
                        <a href="kelola_antrean.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </form>
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
