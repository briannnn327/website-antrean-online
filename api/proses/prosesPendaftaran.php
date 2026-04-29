<?php
ob_start();
require_once __DIR__ . '/../service/koneksi.php';
require_once __DIR__ . '/../service/auth.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!$koneksi) {
        setcookie('flash_error', 'Koneksi database terputus!', time()+10, '/', '', true, false);
        header("Location: /login");
        exit();
    }

    $nama    = htmlspecialchars($_POST['nama_pasien']);
    $nik     = htmlspecialchars($_POST['nik']);
    $poli    = htmlspecialchars($_POST['poli']);
    $tanggal = $_POST['tanggal'];

    $huruf_poli = strtoupper(substr($poli, 5, 1));

    $query_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan='$tanggal' AND poli='$poli'");
    $data_count  = mysqli_fetch_assoc($query_count);
    $urutan_berikutnya = $data_count['total'] + 1;

    $nomor = $huruf_poli . "-" . str_pad($urutan_berikutnya, 3, '0', STR_PAD_LEFT);

    $sql = "INSERT INTO antrian (nama_pasien, nik, poli, tanggal_kunjungan, nomor_antrean)
            VALUES ('$nama', '$nik', '$poli', '$tanggal', '$nomor')";

    if (mysqli_query($koneksi, $sql)) {
        $tgl_format = date('d M Y', strtotime($tanggal));
        header("Location: ../pendaftaran.php?status=sukses&nomor=$nomor&nama=$nama&poli=$poli&tgl=$tgl_format");
        exit();
    } else {
        setcookie('flash_error', 'Gagal menyimpan data: ' . mysqli_error($koneksi), time()+10, '/', '', true, false);
        header("Location: ../pendaftaran.php");
        exit();
    }
}
ob_end_flush();