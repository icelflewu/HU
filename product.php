<?php
include 'koneksi.php';

$sql = "SELECT *, harga FROM product ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="product.css">
    <script src="script.js" defer></script>
</head>
<body>
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

    <main>
    <div id="product1">
    <h1>Product</h1>
    </div>
    <section id="product">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <article>
                    <h3><?php echo $row['judul']; ?></h3>
                    <?php if (!empty($row['gambar'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo $row['judul']; ?>" style="max-width: 100%; height: auto;">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>
                    <p><strong>Rp. <?php echo number_format($row['harga'], 2); ?></strong></p>
                    <button class="add-to-cart" data-product-id="<?php echo $row['id']; ?>">Add to Cart</button>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </section>

    </main>

    <script>
        const addToCartButtons = document.querySelectorAll('.add-to-cart');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.productId;
                addToCart(productId);
            });
        });

        function addToCart(productId) {
            // Logika untuk menambahkan produk ke keranjang belanja menggunakan AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send('product_id=' + productId);
        }
    </script>
</body>
</html>