<?php
//Bagian Awal: Memulai session dan memanggil koneksi database.
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
require '../service/koneksi.php';

// Proteksi Keamanan: Hanya super_admin yang boleh akses halaman ini (mencegah akses dari admin lainnya).
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'super_admin') {
    header("Location: /api/login.php"); 
    exit();
}

// Query Admin List: Mengambil semua user yang memiliki role admin (super, user, atau antrean) dari database.
$admins = mysqli_query($koneksi, "SELECT * FROM user WHERE role IN ('super_admin', 'admin_user', 'admin_antrean') ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>
    <!-- Sidebar Admin: Menu navigasi khusus untuk super_admin dengan akses ke semua fitur kelola. -->
    <div class="sidebar admin-theme">
        <div class="sidebar-header"><i class="fas fa-shield-alt brand-icon"></i> Admin Panel</div>
        <div class="sidebar-section">Dashboard</div>
        <ul class="sidebar-menu">
            <li><a href="../dashboardAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        </ul>
        <!-- Sidebar Role-Based Menu: Menampilkan semua menu kelola untuk super_admin. -->
        <div class="sidebar-section">Kelola</div>
        <ul class="sidebar-menu">
            <!-- Kelola User: Link untuk mengelola user biasa. -->
            <li><a href="kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
            <!-- Kelola Admin (Active): Link untuk mengelola admin (page ini, diberi class active). -->
            <li><a href="kelola_admin.php" class="active"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <!-- Kelola Antrean: Link untuk mengelola antrean. -->
            <li><a href="kelola_antrean.php"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <!-- Navbar Admin: Menampilkan nama admin dan role "Super Admin" yang sedang login. -->
            <div class="nav-user">
                <i class="fas fa-user-shield"></i> 
                Admin: <span><?= htmlspecialchars($_SESSION['nama']) ?> (Super Admin)</span>
            </div>
            <a href="../../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <!-- Page Header: Judul halaman dan tombol untuk menambah admin baru (hanya super_admin yang lihat). -->
            <div class="page-header">
                <h2><i class="fas fa-user-shield"></i> Kelola Admin</h2>
                <a href="tambah_admin.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Admin</a>
            </div>

            <!-- Alert Messages: Menampilkan pesan success atau error dari proses sebelumnya (tambah, edit, hapus admin). -->
            <?php if(isset($_SESSION['success'])){ echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
            <?php if(isset($_SESSION['error'])){ echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); } ?>

            <!-- Tabel Admin: Menampilkan daftar semua admin dengan kolom No., Nama, Email, Role, dan Aksi (Edit/Hapus). -->
            <div class="card">
                <div class="table-wrap">
                    <!-- Table Header Row: Baris header dengan kolom-kolom untuk menampilkan data admin. -->
                    <table>
                        <thead>
                            <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <!-- PHP Loop Admin: Perulangan untuk menampilkan setiap data admin dari hasil query. -->
                        <?php $no=1; while($row = mysqli_fetch_assoc($admins)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <!-- Badge Role Logic: Menampilkan badge warna berbeda sesuai dengan role admin (Super Admin=merah, Admin User=biru, Admin Antrean=hijau). -->
                                <td>
                                    <?php 
                                    // Label badge sesuai role agar informatif
                                    if($row['role'] == 'super_admin') echo '<span class="badge badge-red">Super Admin</span>';
                                    elseif($row['role'] == 'admin_user') echo '<span class="badge badge-blue">Admin User</span>';
                                    elseif($row['role'] == 'admin_antrean') echo '<span class="badge badge-green">Admin Antrean</span>';
                                    ?>
                                </td>
                                <!-- Action Buttons: Tombol Edit dan Hapus untuk setiap admin. Jika ID sama dengan session (akun sendiri), tampilkan teks "Akun Anda". -->
                                <td>
                                    <?php if($row['id'] != $_SESSION['id']) : ?>
                                    <!-- Edit Button: Link ke halaman edit_admin.php untuk mengubah data admin. -->
                                    <a href="edit_admin.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <!-- Hapus Button: Link ke proses hapus dengan konfirmasi dialog untuk menghapus admin. -->
                                    <a href="../proses/prosesHapusUser.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus admin ini?')"><i class="fas fa-trash"></i> Hapus</a>
                                    <?php else : ?>
                                    <!-- Current Account Label: Menampilkan teks "Akun Anda" jika akun yang dilihat adalah akun admin sendiri (tidak bisa edit/hapus sendiri). -->
                                    <span style="color:#aaa; font-size:12px;">Akun Anda</span>
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
</body>
</html>
