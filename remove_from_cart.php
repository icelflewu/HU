<?php
session_start();
include 'koneksi.php';

// Ambil ID item keranjang belanja dari POST
$cart_item_id = isset($_POST['cart_item_id']) ? $_POST['cart_item_id'] : null;
if ($cart_item_id) {
    // Hapus item dari tabel cart berdasarkan ID
    $sql = "DELETE FROM cart WHERE id = $cart_item_id AND session_id = '" . session_id() . "'";
    $conn->query($sql);
}
?>