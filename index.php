<?php
include 'koneksi.php';

$sql = "SELECT * FROM product ORDER BY tanggal DESC LIMIT 3";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <script src="script.js" defer></script>
</head>

<main>
    <nav id="sticky-nav">
        <a href="#" class="logo"><img src="asset/img/logo.jpeg" width="45"></a>
        <div class="nav_content"></div>
        <div class="nav-toggle">
        <a href="#" class="toggle-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
        </div>
        <ul class="menu">
            <li><a href="index.php">HOME</a></li>
            <li><a href="about.php">ABOUT</a></li>
            <li><a href="product.php">PRODUCT</a></li>
            <li><a href="cart.php"><img src="asset/img/cart.png" width="27"></a></li>
            <li><a href="login.php"><img src="asset/img/login.png" width="30"></a></li>
        </ul>
    </nav>
    <header>
        <h1 id="typing"></h1>
        <h2 id="gambar latar">
                <img src="asset/img/360_MIXX.png" alt="Profil">
    </header>
    <section id="About" class="animate full-section">
            <div class="About_container">
                <div class="content">
                    <h1 class="title"><span>A local cocktail with a mix of signature Captikus.</span></h1>
                    <p class="deskripsi">
                    We are present a special local cocktail made with North Sulawesi's signature drink,
                    “Captikus”. The drink consists of four mouth-watering flavors: melon, sunkist, lychee 
                    and strawberry. Each variant offers its own unique flavor, creating an 
                    unforgettable drinking experience. Experience the refreshing freshness of melon, 
                    the sweet acidity of sunkist, the exotic softness of lychee, and the tantalizing 
                    sweetness of strawberry in every sip, all perfectly blended with Captikus' 
                    signature touch.
                    </p>
                </div>
                <div class="gambar">
                    <img src="asset/img/botol.png" alt="Profil">
                </div>
            </div>
        </section>
</main>

    <footer>
        <p>&copy; 2023 Toko Minuman Online. All rights reserved.</p>
    </footer>

</body>
</html>
