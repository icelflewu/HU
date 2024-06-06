<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['update']) && isset($_GET['cartId']) && isset($_GET['quantity'])) {
        $cartId = intval($_GET['cartId']);
        $quantity = intval($_GET['quantity']);

        if ($quantity > 0) {
            $sql = "UPDATE cart SET kuantitas = $quantity WHERE id = $cartId AND session_id = '" . session_id() . "'";
            $conn->query($sql);
        } else {
            $sql = "DELETE FROM cart WHERE id = $cartId AND session_id = '" . session_id() . "'";
            $conn->query($sql);
        }
    }
}
?>