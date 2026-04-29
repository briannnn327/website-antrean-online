<?php
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port = 4000;
$user = '2EprtvkX6uKYQg3.root';
$pass = 'i39wtc5iGyqBTPoJ';
$db   = 'brian_healty';

$koneksi = mysqli_init();

mysqli_ssl_set($koneksi, NULL, NULL, NULL, NULL, NULL);

$real_connect = mysqli_real_connect(
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
    // Simpan error ke session, jangan echo/die langsung
    session_start();
    $_SESSION['error'] = "Koneksi database gagal: " . mysqli_connect_error();
    header("Location: ../../api/login.php");
    exit();
}