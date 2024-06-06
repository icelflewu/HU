<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

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

// Kosongkan keranjang belanja
$sql = "DELETE FROM cart WHERE session_id = '" . session_id() . "'";
$conn->query($sql);

// Redirect ke halaman konfirmasi pesanan
header("Location: order_confirmation.php?order_id=$order_id");
exit();