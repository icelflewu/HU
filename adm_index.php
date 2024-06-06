<?php
// Mulai sesi untuk mengakses variabel sesi
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<main>
    <nav id="sticky-nav">
        <div class="nav_content"></div>
        <div class="nav-toggle">
         <a href="#" class="toggle-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
         </a>
         </div>
        <ul class="menu">
            <li><a href="adm_index.php">HOME</a></li>
            <li><a href="adm_product.php">PRODUCT</a></li>
            <li><a href="adm_order.php">ORDERS</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="#" id="logout-btn"><img src="asset/img/login.png" width="30"></a></li>
            <?php else: ?>
                <li><a href="login.php"><img src="asset/img/login.png" width="30"></a></li>
            <?php endif; ?>
        </ul>
    </nav>
  
    <header>
        <h1 id="typing"></h1>
    </header>

    <?php if (isset($_SESSION['username'])): ?>
        <div id="logout-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <p>Are you sure you want to log out?</p>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
    const logoutBtn = document.getElementById('logout-btn');
    const logoutModal = document.getElementById('logout-modal');
    const closeBtn = document.querySelector('#logout-modal .close-btn');

    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        logoutModal.style.display = 'block';
    });

    closeBtn.addEventListener('click', function() {
        logoutModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == logoutModal) {
            logoutModal.style.display = 'none';
        }
    });
</script>
</body>
</html>