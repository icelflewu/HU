<?php
// Mulai sesi untuk mengakses variabel sesi
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Proses penyetujuan pesanan
if (isset($_POST['approve_order'])) {
    $order_id = $_POST['order_id'];

    // Update status pesanan menjadi "Diproses"
    $sql = "UPDATE pesanan SET status_pesanan = 'Diproses' WHERE id_pesanan = $order_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>showNotification('Pesanan berhasil disetujui.');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Proses pembatalan pesanan
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    // Update status pesanan menjadi "Dibatalkan"
    $sql = "UPDATE pesanan SET status_pesanan = 'Dibatalkan' WHERE id_pesanan = $order_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>showNotification('Pesanan berhasil dibatalkan.');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Proses penyelesaian pesanan
if (isset($_POST['complete_order'])) {
    $order_id = $_POST['order_id'];

    // Update status pesanan menjadi "Selesai"
    $sql = "UPDATE pesanan SET status_pesanan = 'Selesai' WHERE id_pesanan = $order_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>showNotification('Pesanan berhasil diselesaikan.');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data pesanan dari database dengan join ke tabel pelanggan
$sql = "SELECT p.id_pesanan, pe.nama, p.tanggal_pesanan, p.status_pesanan
        FROM pesanan p
        JOIN pelanggan pe ON p.id_pelanggan = pe.id_pelanggan
        ORDER BY p.tanggal_pesanan DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function showNotification(message) {
            if (Notification.permission === 'granted') {
                new Notification('Pesanan', {
                    body: message
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(function (permission) {
                    if (permission === 'granted') {
                        new Notification('Pesanan', {
                            body: message
                        });
                    }
                });
            }
        }
    </script>
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

    <main>
        <section id="orders">
            <h2>Orders</h2>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Orders ID</th>
                        <th>Customers Name</th>
                        <th>Date</th>
                        <th>Orders Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_pesanan']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['tanggal_pesanan']; ?></td>
                            <td><?php echo $row['status_pesanan']; ?></td>
                            <td>
                                <?php if ($row['status_pesanan'] == 'Baru'): ?>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id_pesanan']; ?>">
                                        <button type="submit" name="approve_order">Approve</button>
                                        <button type="submit" name="cancel_order">Cancel</button>
                                    </form>
                                <?php elseif ($row['status_pesanan'] == 'Diproses'): ?>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id_pesanan']; ?>">
                                        <button type="submit" name="complete_order">Finish</button>
                                    </form>
                                <?php elseif ($row['status_pesanan'] == 'Selesai'): ?>
                                    <span>Complete</span>
                                <?php else: ?>
                                    <span>Canceled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No orders were found.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php if (isset($_SESSION['username'])): ?>
        <div id="logout-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <p>Are you sure you want to log out?</p>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </div>
    <?php endif; ?>

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