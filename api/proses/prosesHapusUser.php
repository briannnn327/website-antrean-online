<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan.
session_start();
require __DIR__ . '/../service/koneksi.php';
// Daftar Role yang Diizinkan: Hanya super_admin dan admin_user yang bisa menghapus user/admin.
$allowed_roles = ['super_admin', 'admin_user'];
if (!isset($_SESSION['id']) || !in_array($auth['role'], $allowed_roles)) {
    header("Location: ../dashboardAdmin.php"); exit();
}

// Pengambilan ID: Mengambil ID user yang akan dihapus dari URL parameter GET dan konversi ke integer.
$id   = intval($_GET['id']);
// Ambil Role User: Mengambil data role user untuk menentukan redirect yang tepat setelah hapus (ke kelola_admin atau kelola_user).
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT role FROM user WHERE id='$id'"));

// Validasi Akun Sendiri: Mencegah user menghapus akun sendiri untuk keamanan.
if ($id == $_SESSION['id']) {
    $_SESSION['error'] = "Tidak bisa menghapus akun sendiri!";
    header("Location: ../admin/kelola_admin.php"); exit();
}

// Query Hapus: Menjalankan perintah DELETE untuk menghapus user dari tabel 'user'.
if (mysqli_query($koneksi, "DELETE FROM user WHERE id='$id'")) {
    $_SESSION['success'] = "Data berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus data.";
}

// Tentukan Redirect: Jika user yang dihapus adalah admin, redirect ke kelola_admin. Jika user biasa, redirect ke kelola_user.
$admin_roles = ['super_admin', 'admin_user', 'admin_antrean'];
$redirect = ($data && in_array($data['role'], $admin_roles)) ? 'kelola_admin' : 'kelola_user';
header("Location: ../admin/$redirect.php");
exit();
?>
