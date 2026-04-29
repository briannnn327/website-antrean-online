<?php
ob_start();
require __DIR__ . '/../service/koneksi.php';
require __DIR__ . '/../service/auth.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = intval($_POST['id']);
    $nama    = htmlspecialchars($_POST['nama_pasien']);
    $nik     = htmlspecialchars($_POST['nik']);
    $poli    = htmlspecialchars($_POST['poli']);
    $tanggal = $_POST['tanggal'];
    $nomor   = htmlspecialchars($_POST['nomor_antrean']);

    $sql = "UPDATE antrian SET nama_pasien='$nama', nik='$nik', poli='$poli',
            tanggal_kunjungan='$tanggal', nomor_antrean='$nomor' WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {
        setcookie('flash_success', 'Data antrean berhasil diupdate!', time()+10, '/', '', true, false);
    } else {
        setcookie('flash_error', 'Gagal mengupdate data.', time()+10, '/', '', true, false);
    }
    header("Location: ../admin/kelola_antrean.php");
    exit();
}
ob_end_flush();