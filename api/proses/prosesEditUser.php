<?php
ob_start();
require __DIR__ . '/../service/koneksi.php';
require __DIR__ . '/../service/auth.php';

$auth = get_auth();
$allowed_roles = ['super_admin', 'admin_user'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = intval($_POST['id']);
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $redirect = $_POST['redirect'] ?? 'kelola_user';
    $role     = $_POST['role'] ?? 'user';

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "UPDATE user SET nama='$nama', email='$email', password='$hash', role='$role' WHERE id='$id'";
    } else {
        $sql = "UPDATE user SET nama='$nama', email='$email', role='$role' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        setcookie('flash_success', 'Data berhasil diupdate!', time()+10, '/', '', true, false);
    } else {
        setcookie('flash_error', 'Gagal mengupdate data.', time()+10, '/', '', true, false);
    }
    header("Location: ../admin/$redirect.php");
    exit();
}
ob_end_flush();