<?php
require_once __DIR__ . '/service/auth.php';
require_once __DIR__ . '/service/koneksi.php';

$auth = get_auth();
if (!$auth || $auth['role'] != 'user') {
    header("Location: /login");
    exit();
}

// Poli Pre-selected: Mengambil parameter 'poli' dari URL jika user datang dari halaman layanan (untuk pre-select dropdown).
$poli_selected = $_GET['poli'] ?? '';
// Modal Success Check: Mengecek apakah ada parameter 'status=sukses' untuk menampilkan modal sukses.
$show_modal = isset($_GET['status']) && $_GET['status'] == 'sukses';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrianHealty - Form Antrean</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>
    <!-- Sidebar User: Menu navigasi untuk user dengan pendaftaran.php sebagai active. -->
    <div class="sidebar">
        <!-- Sidebar Header: Logo aplikasi "BrianHealty". -->
        <div class="sidebar-header"><i class="fas fa-hand-holding-medical brand-icon"></i> BrianHealty</div>
        <!-- Sidebar Menu: Daftar menu dengan pendaftaran.php dimark sebagai active. -->
        <div class="sidebar-section">Menu</div>
        <!-- Navigation Links: Menu navigasi dengan Daftar Antrean sebagai active (halaman saat ini). -->
        <ul class="sidebar-menu">
            <li><a href="beranda.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="layanan.php"><i class="fas fa-hospital"></i> Layanan Poli</a></li>
            <li><a href="pendaftaran.php" class="active"><i class="fas fa-clipboard-list"></i> Daftar Antrean</a></li>
            <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat Antrean</a></li>
        </ul>
        <div class="sidebar-section">Akun</div>
        <ul class="sidebar-menu">
            <li><a href="../index.html"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
         <!-- Navbar: Baris atas dengan greeting user dan tombol logout. -->
        <div class="navbar">
            <!-- User Greeting: Menampilkan nama user yang login. -->
            <div class="nav-user"><i class="fas fa-user-circle"></i> Halo, <span><?= htmlspecialchars($auth['nama']) ?></span></div>
            <!-- Logout Button: Tombol untuk logout. -->
            <a href="../index.html" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <div class="page-header">
                <h2><i class="fas fa-clipboard-list"></i> Pendaftaran Antrean Online</h2>
            </div>

            <div class="card" style="max-width:1000px;">
                <h3>Isi Form Pendaftaran</h3>
                <form action="proses/prosesPendaftaran.php" method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap Pasien</label>
                        <input type="text" name="nama_pasien" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label>NIK (16 Digit)</label>
                        <input type="text" name="nik" required maxlength="16" placeholder="Masukkan 16 digit NIK">
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Pilih Poliklinik</label>
                            <select name="poli" required>
                                <option value="">-- Pilih Poli --</option>
                                <option value="Poli Umum"   <?= $poli_selected=='Poli Umum'?'selected':'' ?>>Poli Umum</option>
                                <option value="Poli Gigi"   <?= $poli_selected=='Poli Gigi'?'selected':'' ?>>Poli Gigi</option>
                                <option value="Poli Anak"   <?= $poli_selected=='Poli Anak'?'selected':'' ?>>Poli Anak</option>
                                <option value="Poli Mata"   <?= $poli_selected=='Poli Mata'?'selected':'' ?>>Poli Mata</option>
                                <option value="Poli Jantung"<?= $poli_selected=='Poli Jantung'?'selected':'' ?>>Poli Jantung</option>
                                <option value="Poli Saraf"  <?= $poli_selected=='Poli Saraf'?'selected':'' ?>>Poli Saraf</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kunjungan</label>
                            <input type="date" name="tanggal" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn-submit"><i class="fas fa-check-circle"></i> Ambil Nomor Antrean</button>
                        <a href="layanan.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES: Modal yang muncul setelah pendaftaran berhasil dengan nomor antrean, nama, poli, dan tanggal -->
    <!-- Modal Overlay: Overlay backdrop dengan conditional class 'show' untuk menampilkan/menyembunyikan modal. -->
    <div class="modal-overlay <?= $show_modal ? 'show' : '' ?>">
        <!-- Modal Box: Kotak modal yang berisi informasi sukses. -->
        <div class="modal-box">
            <!-- Modal Header Success: Header dengan icon success dan judul "Berhasil Terdaftar!". -->
            <div class="modal-header-success">
                <i class="fas fa-check-circle"></i>
                <h3>Berhasil Terdaftar!</h3>
            </div>
            <!-- Modal Body: Isi modal dengan nomor antrean dan detail pendaftaran. -->
            <div class="modal-body">
                <!-- Label Nomor Antrean: Teks label untuk nomor antrean yang disalin dari query string. -->
                <p style="text-align:center; color:#888; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Nomor Antrean Anda</p>
                <!-- Display Nomor: Menampilkan nomor antrean yang digenerate (contoh: U-001). -->
                <div class="modal-number"><?= htmlspecialchars($_GET['nomor'] ?? '') ?></div>
                <!-- Modal Info: Menampilkan detail pendaftaran (nama pasien, poli, tanggal). -->
                <div class="modal-info">
                    <p><strong>Pasien:</strong> <span><?= htmlspecialchars($_GET['nama'] ?? '') ?></span></p>
                    <p><strong>Poli:</strong> <span><?= htmlspecialchars($_GET['poli'] ?? '') ?></span></p>
                    <p><strong>Tanggal:</strong> <span><?= htmlspecialchars($_GET['tgl'] ?? '') ?></span></p>
                </div>
                <!-- Finish Button: Tombol untuk menutup modal dan kembali ke dashboard. -->
                <button class="btn-submit" onclick="window.location.href='beranda.php'">
                    <i class="fas fa-home"></i> Selesai
                </button>
            </div>
        </div>
    </div>
</body>
</html>

