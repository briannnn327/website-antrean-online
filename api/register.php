<?php 
// Pastikan path session tetap sama saat menggunakan PHP di Vercel.
ini_set('session.save_path', '/tmp');
//  Bagian Session: Memulai session untuk menangkap data sementara, seperti pesan error jika pendaftaran gagal.
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Daftar Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

    <div class="auth-card">
        <div class="brand"><i class="fas fa-hand-holding-medical"></i> BrianHealty</div>
        <h2>Buat Akun Baru</h2>

        <?php 
        //Logika PHP untuk mengecek apakah ada error dari proses pendaftaran sebelumnya.
        if(isset($_SESSION['error'])) { 
            echo "<div class='alert alert-error'>".$_SESSION['error']."</div>"; 
            unset($_SESSION['error']); 
        } 
        ?>

        <form action="proses/prosesRegister.php" method="POST">
            
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Buat password" required>
            </div>

            <button type="submit" class="btn btn-register">Daftar Sekarang</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="login.php">Login di sini</a><br><br>
            <a href="../index.html" style="color:#888; font-size:12px;"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>