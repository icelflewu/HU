<?php
// Mulai sesi untuk mengakses variabel sesi
session_start();

// Hapus semua data sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();