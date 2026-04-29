<?php
// Bagian Logout: Menghapus semua session user yang sedang login dan menghancurkan session.
session_start();
session_unset();          // Menghapus semua variabel dalam session
session_destroy();        // Menghancurkan session yang sedang berlangsung
header("Location: index.html");  // Redirect kembali ke halaman utama setelah logout
exit();
?>

<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.html");
exit();
