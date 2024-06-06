<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

// Ambil data pesanan dari parameter URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
$total_payment = isset($_GET['total_payment']) ? $_GET['total_payment'] : 0;

// Ambil data pesanan dari tabel pesanan
$sql = "SELECT p.nama, pe.tanggal_pesanan, pe.total_pembayaran, pe.status_pesanan, pe.harga, pe.jumlah_produk
        FROM pesanan pe
        JOIN pelanggan p ON pe.id_pelanggan = p.id_pelanggan
        WHERE pe.id_pesanan = $order_id";
$result = $conn->query($sql);
$order_data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        // Fungsi untuk mengecek status pesanan setiap 5 detik
        setInterval(function() {
            checkOrderStatus(<?php echo $order_id; ?>);
        }, 5000);

        function checkOrderStatus(orderId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "check_order_status.php?order_id=" + orderId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var orderStatus = xhr.responseText;
                    var orderStatusElement = document.getElementById("order-status");
                    if (orderStatus === "Diproses") {
                        orderStatusElement.innerHTML = "On Process";
                    } else {
                        orderStatusElement.innerHTML = orderStatus;
                    }
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <main>
    <nav id="sticky-nav">
        <a href="#" class="logo">360 MIXX</a>
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
        </ul>
    </nav>

        <section id="order-confirmation">
            <h2>Order Confirmation</h2>
            <p>Thank you for your order, <strong><?php echo isset($order_data['nama']) ? $order_data['nama'] : ''; ?>!</strong></p>
            <p>Your order details:</p>
            <ul>
                <li>Order ID: <?php echo $order_id; ?></li>
                <li>Order Date: <?php echo isset($order_data['tanggal_pesanan']) ? $order_data['tanggal_pesanan'] : ''; ?></li>
            </ul>
            <p>We will process your order and ship it as soon as possible.</p>
            <p>Thank you for shopping with us!</p>
        </section>
    </main>
</body>
</html>