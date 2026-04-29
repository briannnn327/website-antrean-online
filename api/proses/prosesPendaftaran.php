<?php
ob_start();
require_once __DIR__ . '/../service/koneksi.php';
require_once __DIR__ . '/../service/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$koneksi) {
        setcookie('flash_error', 'Koneksi database terputus!', time()+10, '/', '', true, false);
        header("Location: /login");
        exit();
    }

// Verifikasi Metode: Pastikan data dikirim melalui metode POST dari form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pengambilan & Pembersihan Data: Mengamankan input dari karakter berbahaya (XSS).
    $nama    = htmlspecialchars($_POST['nama_pasien']); 
    $nik     = htmlspecialchars($_POST['nik']);         
    $poli    = htmlspecialchars($_POST['poli']);        
    $tanggal = $_POST['tanggal'];                       

    // Logika Kode Poli: Mengambil inisial poli (Contoh: "Poli Umum" -> index ke-5 adalah "U").
    // strtoupper memastikan huruf kapital, substr mengambil 1 karakter setelah kata "Poli ".
    $huruf_poli = strtoupper(substr($poli, 5, 1)); 

    // Cek Database: Menghitung jumlah pendaftar pada tanggal dan poli yang sama untuk menentukan nomor urut.
    $query_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM antrian WHERE tanggal_kunjungan='$tanggal' AND poli='$poli'");
    $data_count  = mysqli_fetch_assoc($query_count);
    $urutan_berikutnya = $data_count['total'] + 1;

    // Format Nomor Antrean: Menggabungkan inisial poli dengan nomor urut (Contoh: U-001).
    // str_pad digunakan agar nomor selalu memiliki 3 digit (001, 002, dst).
    $nomor = $huruf_poli . "-" . str_pad($urutan_berikutnya, 3, '0', STR_PAD_LEFT);

    // Query Insert: Memasukkan data pendaftaran ke dalam tabel 'antrian'.
    // PERBAIKAN: Menambahkan kurung tutup ')' yang hilang setelah variabel $nomor.
    $sql = "INSERT INTO antrian (nama_pasien, nik, poli, tanggal_kunjungan, nomor_antrean)
            VALUES ('$nama', '$nik', '$poli', '$tanggal', '$nomor')";

    // Eksekusi: Jika penyimpanan berhasil, arahkan kembali ke form dengan data untuk modal sukses.
    if (mysqli_query($koneksi, $sql)) {
        $tgl_format = date('d M Y', strtotime($tanggal));
        
        // Mengirim data via URL agar ditangkap oleh modal di pendaftaran.php.
        header("Location: ../pendaftaran.php?status=sukses&nomor=$nomor&nama=$nama&poli=$poli&tgl=$tgl_format");
        exit();
    } else {
        // Menampilkan pesan error jika terjadi kegagalan sistem.
        echo "Error: " . mysqli_error($koneksi);
    }

} // <-- Tutup blok if REQUEST_METHOD