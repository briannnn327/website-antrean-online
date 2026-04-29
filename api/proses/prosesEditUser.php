<?php
// Bagian Awal: Memulai session, memanggil koneksi database, dan melakukan pengecekan keamanan.
session_start();
require '../service/koneksi.php';
// Daftar Role yang Diizinkan: Hanya super_admin dan admin_user yang bisa mengedit user.
$allowed_roles = ['super_admin', 'admin_user'];
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../dashboardAdmin.php"); exit();
}

// Pengecekan Metode: Memastikan data dikirim menggunakan method POST dari form edit.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengambilan Input: Mengambil data dari form edit user dan membersihkannya.
    $id       = intval($_POST['id']);                            // ID user yang akan diupdate
    $nama     = htmlspecialchars($_POST['nama']);                // Nama user yang diubah
    $email    = htmlspecialchars($_POST['email']);               // Email user yang diubah
    $password = $_POST['password'];                              // Password baru (opsional, jika kosong tidak diubah)
    $redirect = $_POST['redirect'] ?? 'kelola_user';             // Page untuk redirect setelah update
    $role     = $_POST['role'] ?? 'user';                        // Role user (user atau admin)

    // Pengecekan Password: Jika ada password baru, hash dan sertakan dalam query. Jika kosong, skip password update.
    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "UPDATE user SET nama='$nama', email='$email', password='$hash', role='$role' WHERE id='$id'";
    } else {
        // Jika password kosong, update hanya nama, email, dan role (tanpa password).
        $sql = "UPDATE user SET nama='$nama', email='$email', role='$role' WHERE id='$id'";
    }

    // Eksekusi Query: Menjalankan query update. Jika berhasil, set pesan success.
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['success'] = "Data berhasil diupdate!";

        // Update Session: Jika user sedang update datanya sendiri, update session agar data yang ditampilkan terbaru.
        if ($id == $_SESSION['id']) {
            $_SESSION['nama'] = $nama;
            $_SESSION['role'] = $role;
        }   
    } else {
        $_SESSION['error'] = "Gagal mengupdate data.";
    }
    // Redirect: Kembali ke halaman yang ditentukan ($redirect) setelah proses selesai.
    header("Location: ../admin/$redirect.php");
    exit();
}
?>
