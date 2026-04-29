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
    $nama    = htmlspecialchars($_POST['nama_pasien']);
    $nik     = htmlspecialchars($_POST['nik']);
    $poli    = htmlspecialchars($_POST['poli']);
    $tanggal = $_POST['tanggal'];

    $inisial = "B";
    if ($poli == "Poli Umum")    $inisial = "U";
    if ($poli == "Poli Gigi")    $inisial = "G";
    if ($poli == "Poli Anak")    $inisial = "A";
    if ($poli == "Poli Mata")    $inisial = "M";
    if ($poli == "Poli Jantung") $inisial = "J";
    if ($poli == "Poli Saraf")   $inisial = "S";

    $result_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan='$tanggal' AND poli='$poli'");
    $count_q = mysqli_fetch_assoc($result_count);
    $nomor = $inisial . "-" . str_pad($count_q['total'] + 1, 3, '0', STR_PAD_LEFT);

    $sql = "INSERT INTO antrian (nama_pasien, nik, poli, tanggal_kunjungan, nomor_antrean) 
            VALUES ('$nama', '$nik', '$poli', '$tanggal', '$nomor')";

    if (mysqli_query($koneksi, $sql)) {
        setcookie('flash_success', "Antrean berhasil ditambahkan! Nomor: $nomor", time()+10, '/', '', true, false);
        header("Location: ../admin/kelola_antrean.php");
    } else {
        setcookie('flash_error', 'Gagal menyimpan data: ' . mysqli_error($koneksi), time()+10, '/', '', true, false);
        header("Location: ../admin/tambah_antrean.php");
    }
    exit();
}
ob_end_flush();