<?php
//Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan (hanya admin yang bisa edit).
session_start();
require __DIR__ . '/../service/koneksi.php';
// Daftar Role yang Diizinkan: Semua tipe admin boleh mengubah data antrean.
$allowed_roles = ['super_admin', 'admin_user', 'admin_antrean'];
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../dashboardAdmin.php"); exit();
}

// Pengecekan Metode: Memastikan data dikirim menggunakan method POST dari form edit.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil semua data dari form edit antrean dan membersihkannya.
    $id      = intval($_POST['id']);                                        // ID antrean yang akan diupdate
    $nama    = htmlspecialchars($_POST['nama_pasien']);                   // Nama pasien yang diubah
    $nik     = htmlspecialchars($_POST['nik']);                           // NIK pasien yang diubah
    $poli    = htmlspecialchars($_POST['poli']);                          // Poli yang dipilih
    $tanggal = $_POST['tanggal'];                                          // Tanggal kunjungan yang diubah
    $nomor   = htmlspecialchars($_POST['nomor_antrean']);                 // Nomor antrean yang diubah

    // Query Update: Menyiapkan perintah SQL UPDATE untuk mengubah data antrean di tabel 'antrian'.
    $sql = "UPDATE antrian SET nama_pasien='$nama', nik='$nik', poli='$poli',
            tanggal_kunjungan='$tanggal', nomor_antrean='$nomor' WHERE id='$id'";

    // Eksekusi Query: Menjalankan query update. Jika berhasil, set pesan success, jika gagal set error.
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Data antrean berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate data.";
    }
    // Redirect: Kembali ke halaman kelola_antrean.php setelah proses selesai.
    header("Location: ../admin/kelola_antrean.php");
    exit();
}
?>
