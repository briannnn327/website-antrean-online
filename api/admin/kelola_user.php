<?php
require_once __DIR__ . '/../service/auth.php';
require_once __DIR__ . '/../service/koneksi.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login");
    exit();
}
// Query Users: Mengambil semua user dengan role 'user' (bukan admin) dan mengurutkannya dari ID terbaru.
$users = mysqli_query($koneksi, "SELECT * FROM user WHERE role='user' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>
    <!-- Sidebar Admin: Menu navigasi dengan fitur yang bisa diakses admin (hanya super_admin dan admin_user yang lihat Kelola User). -->
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
        <!-- Navbar Header: Menampilkan informasi admin yang sedang login dan tombol logout. -->
        <div class="navbar">
            <div class="nav-user"><i class="fas fa-user-shield"></i> Admin: <span><?= htmlspecialchars($auth['nama']) ?></span></div>
            <a href="../../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <!-- Page Title dan Button: Judul halaman dan tombol untuk menambah user baru. -->
            <div class="page-header">
                <h2><i class="fas fa-users"></i> Kelola User</h2>
                <a href="tambah_user.php" class="btn-add"><i class="fas fa-plus"></i> Tambah User</a>
            </div>

            <!-- Alert Messages: Menampilkan pesan sukses atau error dari proses sebelumnya (tambah, edit, hapus), lalu menghapusnya dari session. -->
            <?php if(isset($_SESSION['success'])){ echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
            <?php if(isset($_SESSION['error'])){ echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); } ?>

            <!-- Tabel User: Menampilkan daftar semua user dengan kolom untuk No., Nama, Email, Role, dan Aksi (Edit/Hapus). -->
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <!-- PHP Loop: Perulangan untuk menampilkan setiap baris user dari hasil query ke dalam baris tabel. -->
                        <?php $no=1; while($row = mysqli_fetch_assoc($users)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><span class="badge badge-blue">User</span></td>
                                <td>
                                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="../proses/prosesHapusUser.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i> Hapus</a>
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
