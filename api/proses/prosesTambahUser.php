<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan.
session_start();
require __DIR__ . '/../service/koneksi.php';

// Daftar Role yang Diizinkan: Hanya super_admin dan admin_user yang bisa menambah user atau admin baru.
$allowed_roles = ['super_admin', 'admin_user'];
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../dashboardAdmin.php"); 
    exit();
}

// Pengecekan Metode: Memastikan data dikirim menggunakan method POST dari form tambah user/admin.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil data dari form tambah dan membersihkannya.
    $nama     = htmlspecialchars($_POST['nama']);       // Nama user/admin baru
    $email    = htmlspecialchars($_POST['email']);     // Email user/admin baru
    $password = $_POST['password'];                     // Password dalam plain text (akan di-hash)
    $role     = $_POST['role'];                         // Role: 'user', 'super_admin', 'admin_user', atau 'admin_antrean'

    // Validasi Email Unik: Mengecek apakah email sudah terdaftar. Jika ada, tampilkan error dan redirect ke form tambah.
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        // Tentukan redirect sesuai role yang diinput (user ke tambah_user, admin ke tambah_admin).
        $redirect = ($role === 'user') ? ('tambah_user') : 'tambah_admin';
        header("Location: ../admin/$redirect.php"); exit();
    }

    // Hashing Password: Mengenkripsi password menggunakan password_hash() untuk keamanan.
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Query Insert: Menyiapkan perintah SQL untuk memasukkan user/admin baru ke tabel 'user'.
    $sql  = "INSERT INTO user (nama, email, password, role) VALUES ('$nama', '$email', '$hash', '$role')";

    // Eksekusi Query: Menjalankan query insert. Jika berhasil, redirect ke kelola_user atau kelola_admin sesuai role.
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Data $role berhasil ditambahkan!";
        // Tentukan halaman yang akan ditampilkan setelah sukses sesuai role yang ditambahkan.
        $redirect = $role === 'user' ? 'kelola_user' : 'kelola_admin';
        header("Location: ../admin/$redirect.php");
    } else {
        $_SESSION['error'] = "Gagal menyimpan data.";
        // Jika gagal, kembali ke form tambah untuk memasukkan ulang.
        $redirect = $role === 'user' ? 'tambah_user' : 'tambah_admin';
        header("Location: ../admin/$redirect.php");
    }
    exit();
}
?>
