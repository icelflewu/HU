<?php
session_start();
include 'koneksi.php';

// Mengambil total harga dari parameter URL
$total_harga = isset($_GET['total_harga']) ? $_GET['total_harga'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form checkout
    $nama_pelanggan = $_POST['name'];
    $email_pelanggan = $_POST['email'];
    $alamat_pelanggan = $_POST['phone']; // Gunakan nomor ponsel sebagai alamat
    $payment_method = $_POST['payment_method'];

    // Ambil data keranjang belanja dari POST
    $cart_data = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : array();

    // Ambil data harga dan jumlah produk dari tabel cart
    $sql = "SELECT SUM(p.harga * c.kuantitas) AS total_harga, SUM(c.kuantitas) AS jumlah_produk
            FROM cart c
            JOIN product p ON c.product_id = p.id
            WHERE c.session_id = '" . session_id() . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_harga = $row['total_harga'];
    $jumlah_produk = $row['jumlah_produk'];

    // Cek apakah pelanggan sudah ada di tabel pelanggan
    $sql = "SELECT id_pelanggan FROM pelanggan WHERE email = '$email_pelanggan'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Pelanggan sudah ada, ambil id_pelanggan
        $row = $result->fetch_assoc();
        $id_pelanggan = $row['id_pelanggan'];
    } else {
        // Pelanggan belum ada, tambahkan data pelanggan ke tabel pelanggan
        $sql = "INSERT INTO pelanggan (nama, email, alamat) VALUES ('$nama_pelanggan', '$email_pelanggan', '$alamat_pelanggan')";
        $conn->query($sql);
        $id_pelanggan = $conn->insert_id;
    }

    // Masukkan data ke tabel pesanan
$status_pesanan = 'Baru';
$sql = "INSERT INTO pesanan (id_pelanggan, status_pesanan, total_pembayaran, harga, jumlah_produk, tanggal_pesanan)
        VALUES ($id_pelanggan, '$status_pesanan', $total_harga, $total_harga, $jumlah_produk, CURRENT_TIMESTAMP())";
$conn->query($sql);
$order_id = $conn->insert_id;

    // Masukkan data produk yang dibeli ke tabel produk_pesanan
    foreach ($cart_data as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['kuantitas'];
        $harga_produk = $item['harga'];
        $jumlah_pembayaran = $harga_produk * $quantity;

        $sql = "INSERT INTO produk_pesanan (id_pesanan, id_product, kuantitas, jumlah_pembayaran)
                VALUES ($order_id, $product_id, $quantity, $jumlah_pembayaran)";
        $conn->query($sql);
    }

    // Kosongkan keranjang belanja
    $sql = "DELETE FROM cart WHERE session_id = '" . session_id() . "'";
    $conn->query($sql);

    // Redirect ke halaman konfirmasi pesanan
    header("Location: order_confirmation.php?order_id=$order_id&total_payment=$total_harga");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <main>
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

        <section id="checkout">
            <h2>Checkout</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="checkout-form" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required pattern="^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$" title="Masukkan nomor ponsel yang valid (contoh: +628123456789 atau 08123456789)">
                </div>
                <div class="form-group">
                    <label for="payment-amount">Total Payment:</label>
                    <input type="text" id="payment-amount" name="payment_amount" value="<?php echo number_format($total_harga, 2); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment_method">
                        <option value="cash">Cash/COD</option>
                        <option value="bank-transfer|16163094805 - BNI, 180260567851010 - BRI">Bank Transfer</option>
                        <option value="e-wallet">Dana, Ovo, Gopay, ShopeePay</option>
                    </select>
                </div>
                <div class="form-group" id="bank-account-container" style="display: none;">
                    <label for="bank-account">Bank Account Number:</label>
                    <input type="text" id="bank-account" name="bank_account" readonly>
                </div>
                <div class="form-group" id="qr-code-container" style="display: none;">
                    <label>QR Code:</label>
                    <img id="qr-code-img" src="" alt="QR Code" style="max-width: 250px;">
                </div>
                <button type="submit" class="checkout-btn">Confirm</button>
            </form>
        </section>
    </main>
    <script>
    const paymentMethodSelect = document.getElementById('payment-method');
    const bankAccountContainer = document.getElementById('bank-account-container');
    const qrCodeContainer = document.getElementById('qr-code-container');
    const qrCodeImg = document.getElementById('qr-code-img');

    paymentMethodSelect.addEventListener('change', () => {
        const selectedPaymentMethod = paymentMethodSelect.value;

        bankAccountContainer.style.display = 'none';
        qrCodeContainer.style.display = 'none';

        if (selectedPaymentMethod.includes('bank-transfer')) {
            const bankAccount = selectedPaymentMethod.split('|')[1];
            bankAccountContainer.style.display = 'block';
            document.getElementById('bank-account').value = bankAccount;
        } else if (selectedPaymentMethod === 'e-wallet') {
            qrCodeContainer.style.display = 'block';
            qrCodeImg.src = 'asset/img/QR.jpg'; // Ganti dengan path gambar QR code yang sesuai
        }
    });
</script>
</body>
</html>