<?php
// Bagian Awal: Memulai session dan melakukan pengecekan keamanan.
session_start();
// Proteksi Keamanan: Hanya super_admin yang boleh menambah admin baru (untuk mencegah abuse).
if (!isset($_SESSION['id']) || $auth['role'] != 'super_admin') {
    header("Location: ../dashboardAdmin.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tambah Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>
    <div class="sidebar admin-theme">
        <div class="sidebar-header"><i class="fas fa-shield-alt brand-icon"></i> Admin Panel</div>
        <div class="sidebar-section">Dashboard</div>
        <ul class="sidebar-menu">
            <li><a href="../dashboardAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        </ul>
        <div class="sidebar-section">Kelola</div>
        <ul class="sidebar-menu">
            <li><a href="kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
            <li><a href="kelola_admin.php" class="active"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <li><a href="kelola_antrean.php"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <div class="nav-user"><i class="fas fa-user-shield"></i> Admin: <span><?= htmlspecialchars($auth['nama']) ?></span></div>
            <a href="../../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <div class="page-header">
                <h2><i class="fas fa-user-shield"></i> Tambah Admin Baru</h2>
            </div>
            <div class="card" style="max-width:580px;">
                <h3>Form Tambah Admin</h3>
                <!-- Alert Error: Menampilkan pesan error jika ada dari proses tambah sebelumnya. -->
                <?php if(isset($_SESSION['error'])){ echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); } ?>
                
                <!-- Form Tambah Admin: Formulir untuk menambahkan admin baru dengan role berbeda. -->
                <form action="../proses/prosesTambahUser.php" method="POST">
                    <!-- Nama Lengkap Input: Kolom teks untuk memasukkan nama admin baru. -->
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Masukkan nama admin">
                    </div>
                    <!-- Email Input: Kolom email untuk memasukkan alamat email admin baru. -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="contoh@email.com">
                    </div>
                    <!-- Password Input: Kolom password untuk memasukkan password admin baru. -->
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Buat password">
                    </div>
                    
                    <!-- Role Dropdown: Select untuk memilih tipe/jabatan admin (Super Admin, Admin User, atau Admin Antrean). -->
                    <div class="form-group">
                        <label>Jabatan / Role Admin</label>
                        <select name="role" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="">-- Pilih Role --</option>
                            <!-- Super Admin Option: Admin dengan akses penuh ke semua fitur dan user. -->
                            <option value="super_admin">Super Admin (Akses Penuh)</option>
                            <!-- Admin User Option: Admin yang bisa kelola user dan antrean. -->
                            <option value="admin_user">Admin User (Kelola User & Antrean)</option>
                            <!-- Admin Antrean Option: Admin yang hanya bisa kelola antrean. -->
                            <option value="admin_antrean">Admin Antrean (Hanya Kelola Antrean)</option>
                        </select>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:20px;">
                        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Admin</button>
                        <a href="kelola_admin.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
