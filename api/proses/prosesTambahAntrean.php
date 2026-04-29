<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan.
ini_set('session.save_path', '/tmp');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
require __DIR__ . '/../service/koneksi.php';

// Daftar Role yang Diizinkan: Semua level admin boleh memproses penambahan antrean.
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: /api/login.php");
    exit();
}

// Pengecekan Metode: Memastikan data dikirim menggunakan method POST dari form tambah antrean.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil data dari form tambah antrean dan membersihkannya.
    $nama    = htmlspecialchars($_POST['nama_pasien']);  // Nama pasien
    $nik     = htmlspecialchars($_POST['nik']);          // NIK pasien
    $poli    = htmlspecialchars($_POST['poli']);         // Jenis poliklinik
    $tanggal = $_POST['tanggal'];                         // Tanggal kunjungan

    // Tentukan Inisial Poli: Menentukan huruf awal berdasarkan nama poli untuk format nomor antrean (U=Umum, G=Gigi, dll).
    $inisial = "B"; // Default BrianHealty
    if ($poli == "Poli Umum") $inisial = "U";
    if ($poli == "Poli Gigi") $inisial = "G";
    if ($poli == "Poli Anak") $inisial = "A";
    if ($poli == "Poli Mata") $inisial = "M";
    if ($poli == "Poli Jantung") $inisial = "J";
    if ($poli == "Poli Saraf") $inisial = "S";

    // Hitung Nomor Urut: Menghitung berapa banyak antrean sudah ada pada tanggal dan poli yang sama.
    $query_count = "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan='$tanggal' AND poli='$poli'";
    $result_count = mysqli_query($koneksi, $query_count);
    $count_q = mysqli_fetch_assoc($result_count);
    
    // Generate Nomor Antrean: Membuat nomor dengan format Inisial-NomorUrut (contoh: U-001, G-002).
    $nomor = $inisial . "-" . str_pad($count_q['total'] + 1, 3, '0', STR_PAD_LEFT);

    // Query Insert: Menyiapkan perintah SQL untuk menyimpan data antrean baru ke tabel 'antrian'.
    $sql = "INSERT INTO antrian (nama_pasien, nik, poli, tanggal_kunjungan, nomor_antrean) 
            VALUES ('$nama', '$nik', '$poli', '$tanggal', '$nomor')";

    // Eksekusi Query: Menjalankan query insert. Jika berhasil, set success dan redirect, jika gagal set error.
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Antrean berhasil ditambahkan! Nomor: $nomor";
        header("Location: ../admin/kelola_antrean.php");
    } else {
        $_SESSION['error'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
        header("Location: ../admin/tambah_antrean.php");
    }
    exit();
}
?>