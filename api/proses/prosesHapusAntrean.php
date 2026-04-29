<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan (hanya admin yang bisa hapus).
session_start();
require __DIR__ . '/../service/koneksi.php';

// Daftar Role yang Diizinkan: Hanya super_admin, admin_user, dan admin_antrean yang boleh menghapus antrean.
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];

// Pengecekan Autentikasi: Memastikan user sudah login dan memiliki role yang diizinkan. Jika tidak, redirect ke dashboard.
if (!isset($_SESSION['id']) || !in_array($auth['role'], $allowed_roles)) {
    header("Location: ../dashboardAdmin.php"); 
    exit();
}

// Pengambilan ID: Mengambil ID antrean dari URL parameter GET dan mengkonversinya ke integer untuk keamanan.
$id = intval($_GET['id']);

// Query Hapus: Menjalankan perintah DELETE untuk menghapus data antrean dengan ID tertentu dari tabel 'antrian'.
if (mysqli_query($koneksi, "DELETE FROM antrian WHERE id='$id'")) {
    $_SESSION['success'] = "Data antrean berhasil dihapus!";  // Pesan sukses jika delete berhasil
} else {
    $_SESSION['error'] = "Gagal menghapus data.";             // Pesan error jika delete gagal
}

// Redirect: Kembali ke halaman kelola_antrean.php setelah proses hapus selesai.
header("Location: ../admin/kelola_antrean.php");
exit();
?>
