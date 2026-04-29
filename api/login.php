<?php
// Pastikan session dimulai di baris paling atas
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div class="auth-card">
        <div class="brand"><i class="fas fa-hand-holding-medical"></i> BrianHealty</div>
        <h2>Selamat Datang di Sistem Antrean Online</h2>

        <?php if(isset($_SESSION['success'])): ?>
            <div class='alert alert-success'><?php echo $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class='alert alert-error'><?php echo $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="/api/proses/prosesLogin.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password anda" required>
            </div>
            <button type="submit" class="btn">Login Sekarang</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="/api/register.php">Daftar di sini</a><br><br>
            <a href="/" style="color:#888; font-size:12px;"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>