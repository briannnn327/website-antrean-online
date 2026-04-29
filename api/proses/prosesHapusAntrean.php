<?php
ob_start();
require __DIR__ . '/../service/koneksi.php';
require __DIR__ . '/../service/auth.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login"); exit();
}

$id = intval($_GET['id']);

if (mysqli_query($koneksi, "DELETE FROM antrian WHERE id='$id'")) {
    setcookie('flash_success', 'Data antrean berhasil dihapus!', time()+10, '/', '', true, false);
} else {
    setcookie('flash_error', 'Gagal menghapus data.', time()+10, '/', '', true, false);
}

header("Location: ../admin/kelola_antrean.php");
exit();
ob_end_flush();