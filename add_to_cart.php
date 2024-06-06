<?php
session_start();
include 'koneksi.php';

// Logika untuk menambahkan produk ke keranjang belanja
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Cek apakah produk sudah ada di keranjang belanja
    $sql = "SELECT * FROM cart WHERE product_id = $product_id AND session_id = '" . session_id() . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Produk sudah ada di keranjang belanja, perbarui kuantitas
        $row = $result->fetch_assoc();
        $new_quantity = $row['kuantitas'] + 1;
        $sql = "UPDATE cart SET kuantitas = $new_quantity WHERE id = " . $row['id'];
        $conn->query($sql);
        echo "The quantity of products in the shopping cart has been updated.";
    } else {
        // Produk belum ada di keranjang belanja, tambahkan baris baru
        $sql = "INSERT INTO cart (product_id, session_id, kuantitas, tanggal) VALUES ($product_id, '" . session_id() . "', 1, NOW())";
        $conn->query($sql);
        echo "The product has been added to the shopping cart.";
    }
}
?>