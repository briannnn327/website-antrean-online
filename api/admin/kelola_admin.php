<?php
require_once __DIR__ . '/../service/auth.php';
require_once __DIR__ . '/../service/koneksi.php';

$auth = get_auth();
$allowed_roles = ['super_admin'];
if (!$auth || !in_array($auth['role'], $allowed_roles)) {
    header("Location: /login");
    exit();
}

$admins = mysqli_query($koneksi, "SELECT * FROM user WHERE role IN ('super_admin', 'admin_user', 'admin_antrean') ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>

<!-- ✅ OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar admin-theme" id="sidebar">
    <div class="sidebar-header"><i class="fas fa-shield-alt brand-icon"></i> Admin Panel</div>
    <div class="sidebar-section">Dashboard</div>
    <ul class="sidebar-menu">
        <li><a href="../dashboardAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    </ul>
    <div class="sidebar-section">Kelola</div>
    <ul class="sidebar-menu">
        <li><a href="kelola_user.php"><i class="fas fa-users"></i> Kelola User</a></li>
        <li><a href="kelola_admin.php" class="active"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
        <li><a href="kelola_antrean.php"><i class="fas fa-clipboard-list"></i> Kelola Antrean</a></li>
    </ul>
    <div class="sidebar-section">Akun</div>
    <ul class="sidebar-menu">
        <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="navbar">
        <!-- ✅ HAMBURGER -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="nav-user">
            <i class="fas fa-user-shield"></i>
            Admin: <span><?= htmlspecialchars($auth['nama']) ?> (Super Admin)</span>
        </div>
        <a href="../../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="page-header">
            <h2><i class="fas fa-user-shield"></i> Kelola Admin</h2>
            <a href="tambah_admin.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Admin</a>
        </div>

        <?php if (isset($_SESSION['success'])) { echo "<div class='alert alert-success'>".$_SESSION['success']."</div>"; unset($_SESSION['success']); } ?>
        <?php if (isset($_SESSION['error']))   { echo "<div class='alert alert-error'>".$_SESSION['error']."</div>";   unset($_SESSION['error']);   } ?>

        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($admins)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <?php
                                if ($row['role'] == 'super_admin')     echo '<span class="badge badge-red">Super Admin</span>';
                                elseif ($row['role'] == 'admin_user')     echo '<span class="badge badge-blue">Admin User</span>';
                                elseif ($row['role'] == 'admin_antrean') echo '<span class="badge badge-green">Admin Antrean</span>';
                                ?>
                            </td>
                            <td>
                                <?php if ($row['id'] != $auth['id']) : ?>
                                    <a href="edit_admin.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="../proses/prosesHapusUser.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus admin ini?')"><i class="fas fa-trash"></i> Hapus</a>
                                <?php else : ?>
                                    <span style="color:#aaa; font-size:12px;">Akun Anda</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const hamburgerBtn   = document.getElementById('hamburgerBtn');
const sidebar        = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function openSidebar() {
    sidebar.classList.add('open');
    sidebarOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

hamburgerBtn.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
});
sidebarOverlay.addEventListener('click', closeSidebar);

document.querySelectorAll('.sidebar-menu li a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) closeSidebar();
    });
});
</script>

</body>
</html>