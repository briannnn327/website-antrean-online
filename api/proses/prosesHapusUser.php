<?php
ob_start();
require __DIR__ . '/../service/koneksi.php';
require __DIR__ . '/../service/auth.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login"); exit();
}

$id   = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT role FROM user WHERE id='$id'"));

// Cegah hapus akun sendiri
if ($id == $auth['id']) {
    setcookie('flash_error', 'Tidak bisa menghapus akun sendiri!', time()+10, '/', '', true, false);
    header("Location: ../admin/kelola_admin.php"); exit();
}

if (mysqli_query($koneksi, "DELETE FROM user WHERE id='$id'")) {
    setcookie('flash_success', 'Data berhasil dihapus!', time()+10, '/', '', true, false);
} else {
    setcookie('flash_error', 'Gagal menghapus data.', time()+10, '/', '', true, false);
}

$admin_roles = ['super_admin', 'admin_user', 'admin_antrean'];
$redirect = ($data && in_array($data['role'], $admin_roles)) ? 'kelola_admin' : 'kelola_user';
header("Location: ../admin/$redirect.php");
exit();
ob_end_flush();