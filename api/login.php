<?php
// Bagian Session: Memulai session untuk menyimpan data login pengguna seperti id, nama, dan role.
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
     <!-- Bagian Head: Pengaturan dasar halaman (charset, viewport) dan pemanggilan font Poppins, icon FontAwesome, serta file CSS untuk styling form login. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <!-- Auth Card: Kontainer utama berbentuk kotak yang membungkus seluruh elemen form login di tengah layar. -->
    <div class="auth-card">
        <!-- Brand Header: Logo dan nama aplikasi "BrianHealty" di bagian atas form. -->
        <div class="brand"><i class="fas fa-hand-holding-medical"></i> BrianHealty</div>
        <!-- Form Title: Judul ucapan selamat datang untuk pengguna yang akan login. -->
        <h2>Selamat Datang di Sistem Antrean Online</h2>

        <!-- Alert Success: Menampilkan pesan sukses jika ada (misalnya dari registrasi atau logout), lalu menghapusnya dari session. -->
         
        <?php if(isset($_SESSION['success'])) { echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
        <!-- Alert Error: Menampilkan pesan error jika login gagal (email tidak ditemukan atau password salah), lalu menghapusnya. -->
        <?php if(isset($_SESSION['error'])) { echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); } ?>

        <!-- Form Login: Mengirim data email dan password ke prosesLogin.php menggunakan method POST agar tidak terlihat di URL. -->
        <form action="proses/prosesLogin.php" method="POST">
            <!-- Email Input: Kolom teks untuk memasukkan alamat email yang terdaftar. -->
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@gmail.com" required>
            </div>
            <!-- Password Input: Kolom password untuk keamanan. type="password" menyembunyikan karakter yang diketik dengan bintang/titik. -->
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password anda" required>
            </div>
            <!-- Tombol Submit: Mengirim form data ke server untuk diproses. -->
            <button type="submit" class="btn">Login Sekarang</button>
        </form>

        <!-- Auth Footer: Bagian bawah dengan link untuk registrasi (bagi user baru) dan kembali ke halaman utama. -->
        <div class="auth-footer">
            Belum punya akun? <a href="register.php">Daftar di sini</a><br><br>
            <a href="../index.html" style="color:#888; font-size:12px;"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>
    <!-- Script: Pemanggilan file JavaScript eksternal untuk menambah interaksi atau validasi client-side. -->
    <script src="../assets/js/script.js"></script>
</body>
</html>