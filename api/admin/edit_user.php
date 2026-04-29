<?php
require_once __DIR__ . '/../service/auth.php';
require_once __DIR__ . '/../service/koneksi.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login");
    exit();
}
// Pengambilan Data User: Mengambil ID user dari URL parameter GET dan fetch data dari database.
$id = intval($_GET['id']);
$q = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
$data = mysqli_fetch_assoc($q);
// Validasi Data Exists: Jika user tidak ditemukan, tampilkan error dan hentikan eksekusi.
if (!$data) { die("User tidak ditemukan!"); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edit User</title>
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
                <li><a href="kelola_user.php" class="active"><i class="fas fa-users"></i> Kelola User</a></li>
            <?php endif; ?>
            <?php if ($auth['role'] == 'super_admin') : ?>
                <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <?php endif; ?>
            <li><a href="kelola_antrean.php"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
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
                <h2><i class="fas fa-user-edit"></i> Edit User</h2>
            </div>
            <div class="card" style="max-width:580px;">
                <h3>Form Edit User</h3>
                <!-- Form Edit User: Formulir untuk mengubah data user (nama, email, password opsional). -->
                <form action="../proses/prosesEditUser.php" method="POST">
                    <!-- Hidden Inputs: Menyimpan ID user dan halaman redirect setelah update berhasil. -->
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <input type="hidden" name="redirect" value="kelola_user">
                    <!-- Nama Lengkap Input: Kolom teks untuk mengubah nama user. -->
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required value="<?= htmlspecialchars($data['nama']) ?>">
                    </div>
                    <!-- Email Input: Kolom email untuk mengubah alamat email user. -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required value="<?= htmlspecialchars($data['email']) ?>">
                    </div>
                    <!-- Password Input (Opsional): Kolom password untuk mengubah password user. Boleh dikosongkan jika tidak ingin mengubah password. -->
                    <div class="form-group">
                        <label>Password Baru <small style="color:#aaa;">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" placeholder="Password baru (opsional)">
                    </div>
                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update</button>
                        <a href="kelola_user.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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
