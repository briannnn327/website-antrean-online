<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan.
session_start();
require '../service/koneksi.php';

// Daftar Role yang Diizinkan: Semua tipe admin (super_admin, admin_user, admin_antrean) bisa mengakses halaman ini.
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
// Pengecekan Autentikasi: Memastikan user sudah login dan memiliki role yang tepat. Jika tidak, redirect ke dashboard.
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.php"); 
    exit();
}

// Query Antrean: Mengambil semua data antrean dari tabel 'antrian' dan mengurutkannya dari ID terbaru.
$antrean = mysqli_query($koneksi, "SELECT * FROM antrian ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Antrean</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>
    <!-- Sidebar Admin: Menu navigasi dengan pilihan Dashboard, Kelola User, Kelola Admin, dan Kelola Antrean (sesuai role). -->
    <div class="sidebar admin-theme">
        <div class="sidebar-header"><i class="fas fa-shield-alt brand-icon"></i> Admin Panel</div>
        <div class="sidebar-section">Dashboard</div>
        <ul class="sidebar-menu">
            <li><a href="../dashboardAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        </ul>
        <!-- Sidebar Role-Based Menu: Menampilkan menu berbeda tergantung role user yang login (tidak semua admin bisa akses semua menu). -->
        <div class="sidebar-section">Kelola</div>
        <ul class="sidebar-menu">
            <!-- Kelola User Menu: Hanya super_admin dan admin_user yang bisa lihat opsi ini. -->
            <?php if ($_SESSION['role'] == 'super_admin' || $_SESSION['role'] == 'admin_user') : ?>
                <li><a href="kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
            <?php endif; ?>

            <!-- Kelola Admin Menu: Hanya super_admin yang bisa lihat opsi ini untuk mengelola admin lainnya. -->
            <?php if ($_SESSION['role'] == 'super_admin') : ?>
                <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
            <?php endif; ?>

            <!-- Kelola Antrean Menu: Semua tipe admin bisa mengakses (dimark sebagai active). -->
            <li><a href="kelola_antrean.php" class="active"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <div class="nav-user">
                <i class="fas fa-user-shield"></i> 
                Admin: <span><?= htmlspecialchars($_SESSION['nama']) ?> (<?= ucfirst(str_replace('_', ' ', $_SESSION['role'])) ?>)</span>
            </div>
            <a href="../../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <!-- Page Title dan Button: Judul halaman dengan tombol tambah antrean baru. -->
            <div class="page-header">
                <h2><i class="fas fa-clipboard-list"></i> Kelola Antrean</h2>
                <a href="tambah_antrean.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Antrean</a>
            </div>

            <!-- Alert Messages: Menampilkan pesan sukses atau error dari proses operasi sebelumnya, lalu menghapusnya. -->
            <?php if(isset($_SESSION['success'])){ echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
            <?php if(isset($_SESSION['error'])){ echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); } ?>

            <!-- Tabel Antrean: Menampilkan daftar semua antrean dengan kolom No., Nomor Antrean, Nama Pasien, NIK, Poliklinik, Tanggal, dan Aksi. -->
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>#</th><th>No. Antrean</th><th>Nama Pasien</th><th>NIK</th><th>Poliklinik</th><th>Tanggal</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <!-- PHP Loop: Perulangan untuk menampilkan setiap data antrean dari hasil query. -->
                        <?php $no=1; while($row = mysqli_fetch_assoc($antrean)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong style="color:#1a73e8;"><?= $row['nomor_antrean'] ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= $row['nik'] ?></td>
                                <!-- Badge Styling: Menampilkan badge warna berbeda untuk setiap jenis poliklinik (Umum=biru, Gigi=hijau, dll). -->
                                <td>
                                    <?php
                                    $badges = ['Poli Umum'=>'blue','Poli Gigi'=>'green','Poli Anak'=>'purple','Poli Mata'=>'gray','Poli Jantung'=>'red','Poli Saraf'=>'teal'];
                                    $cls = $badges[$row['poli']] ?? 'blue';
                                    ?>
                                    <span class="badge badge-<?= $cls ?>"><?= $row['poli'] ?></span>
                                </td>
                                <td><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                                <!-- Action Buttons: Tombol Edit untuk mengubah data antrean dan Hapus untuk menghapus data dengan konfirmasi. -->
                                <td>
                                    <a href="edit_antrean.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="../proses/prosesHapusAntrean.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus data antrean ini?')"><i class="fas fa-trash"></i> Hapus</a>
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