<?php
session_start();
include 'koneksi.php';

// Logika untuk mengambil data keranjang belanja dari database
$sql = "SELECT c.id, p.id AS product_id, p.judul, p.konten, p.deskripsi, p.tanggal AS product_tanggal, p.gambar, c.kuantitas, c.tanggal AS cart_tanggal, p.harga FROM cart c INNER JOIN product p ON c.product_id = p.id WHERE c.session_id = '" . session_id() . "'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
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
            <li><a href="index.php">HOME</a></li>
            <li><a href="about.php">ABOUT</a></li>
            <li><a href="product.php">PRODUCT</a></li>
            <li><a href="cart.php"><img src="asset/img/cart.png" width="27"></a></li>
            <li><a href="login.php"><img src="asset/img/login.png" width="30"></a></li>
        </ul>
    </nav>
    <main>
        <section id="cart">
            <h2>Shopping Cart</h2>
            <?php if ($result->num_rows > 0): ?>
                <form id="cart-form">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="cart-item">
                            <div class="cart-item-details">
                                <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                                <?php // Filter konten untuk menghilangkan tag HTML ?>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo $row['judul']; ?>">
                                <?php else: ?>
                                    <p>No image available</p>
                                <?php endif; ?>
                                <?php if (!empty($row['deskripsi'])): ?>
                                    <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-actions">
                                <input type="number" name="quantity[<?php echo $row['id']; ?>]" min="1" value="<?php echo $row['kuantitas']; ?>" class="quantity-input" data-price="<?php echo $row['harga']; ?>">
                                <button type="button" name="remove" value="<?php echo $row['id']; ?>" class="remove-from-cart">Delete</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div class="cart-summary">
                        <p><strong>Total Price: </strong><strong><span id="total-harga">0</strong></span></p>
                    </div>
                    <a href="checkout.php" id="checkout-link" class="checkout-btn">Checkout</a>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </section>
    </main>
    <script>
        // Fungsi untuk menghitung total harga
        function calculateTotalPrice() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            let totalPrice = 0;

            quantityInputs.forEach(input => {
                const quantity = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                totalPrice += quantity * price;
            });

            document.getElementById('total-harga').textContent = totalPrice.toFixed(2);
            updateCheckoutLink(totalPrice);
        }

        // Fungsi untuk memperbarui tautan checkout dengan total harga
        function updateCheckoutLink(totalPrice) {
            const checkoutLink = document.getElementById('checkout-link');
            checkoutLink.href = `checkout.php?total_harga=${totalPrice.toFixed(2)}`;
        }

        // Menghitung total harga saat halaman dimuat
        calculateTotalPrice();

        // Menambahkan event listener untuk setiap input kuantitas
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('input', () => {
            calculateTotalPrice();
            updateQuantity(input.name.match(/\d+/)[0], input.value);
        });
    });

    // Fungsi untuk memperbarui kuantitas pada server
    function updateQuantity(cartId, quantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `update_cart.php?update=true&cartId=${cartId}&quantity=${quantity}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Kuantitas berhasil diperbarui
            }
        };
        xhr.send();
    }

        // Event listener untuk tombol "Delete"
        const removeButtons = document.querySelectorAll('.remove-from-cart');
        removeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const cartItemId = button.value;
                removeFromCart(cartItemId);
            });
        });

        function removeFromCart(cartItemId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_from_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Setelah berhasil menghapus item, perbarui halaman keranjang
                    location.reload();
                }
            };
            xhr.send('cart_item_id=' + cartItemId);
        }
    </script>
</body>
</html> 