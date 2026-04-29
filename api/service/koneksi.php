<?php

// Data dari TiDB Cloud
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port = 4000;
$user = '2EprtvkX6uKYQg3.root';
$pass = 'ZywrT1MHuJkMG3ms';
$db   = 'brian_healty';

// Inisialisasi mysqli
$koneksi = mysqli_init();

// Menambahkan pengaturan SSL (Wajib untuk TiDB Serverless)
mysqli_ssl_set($koneksi, NULL, NULL, NULL, NULL, NULL);

// Melakukan koneksi - Tambahkan error reporting yang tidak langsung tampil ke layar
// Menggunakan @ untuk menyembunyikan warning koneksi agar tidak merusak header redirect
$real_connect = @mysqli_real_connect(
    $koneksi, 
    $host, 
    $user, 
    $pass, 
    $db, 
    $port, 
    NULL, 
    MYSQLI_CLIENT_SSL
);

if (!$real_connect) {
    error_log("Koneksi ke TiDB Cloud gagal: " . mysqli_connect_error());
}