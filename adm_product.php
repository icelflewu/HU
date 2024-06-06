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

if (isset($_POST['submit_add'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    // Menangani unggahan gambar untuk konten
    $gambar = null;
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambarTmp = $_FILES['gambar']['tmp_name'];
        $gambarData = file_get_contents($gambarTmp);
        $gambar = mysqli_real_escape_string($conn, $gambarData);
    }

    $sql = "INSERT INTO product (judul, deskripsi, harga, gambar) VALUES ('$judul', '$deskripsi', '$harga', '$gambar')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Proses pengeditan produk
if (isset($_POST['submit_edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    // Menangani unggahan gambar untuk konten
    $gambar = null;

    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambarTmp = $_FILES['gambar']['tmp_name'];
        $gambarData = file_get_contents($gambarTmp);
        $gambar = mysqli_real_escape_string($conn, $gambarData);
        $sql = "UPDATE product SET judul = '$judul', deskripsi = '$deskripsi', harga = '$harga', gambar = '$gambar' WHERE id = $id";
    } else {
        $sql = "UPDATE product SET judul = '$judul', deskripsi = '$deskripsi', harga = '$harga' WHERE id = $id";
    }

     if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product updated successfully.'); window.location.href = 'adm_product.php';</script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM product ORDER BY tanggal DESC";
$result = $conn->query($sql);

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus semua item di keranjang yang mereferensikan produk ini
    $sql_delete_cart = "DELETE FROM cart WHERE product_id = $delete_id";
    if ($conn->query($sql_delete_cart) !== TRUE) {
        echo "Error removing from cart: " . $conn->error;
        exit();
    }

    // Sekarang hapus produk
    $sql_delete_product = "DELETE FROM product WHERE id = $delete_id";
    if ($conn->query($sql_delete_product) === TRUE) {
        echo "Product successfully deleted.";
    } else {
        echo "Error: " . $sql_delete_product . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM product ORDER BY tanggal DESC";
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
        <section id="product">
            <h2>Product</h2>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <article>
                        <h3><?php echo $row['judul']; ?></h3>
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo $row['judul']; ?>">
                        <?php endif; ?>
                        <p><?php echo $row['deskripsi']; ?></p>
                        <p><strong>Rp. <?php echo number_format($row['harga'], 2, ',', '.'); ?></strong></p>
                        <a href="?edit_id=<?php echo $row['id']; ?>">Update</a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Delete</a>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products were found.</p>
            <?php endif; ?>
        </section>

        <?php if (isset($_GET['edit_id'])): ?>
            <div id="edit-product">
                <div id="edit-product-content">
                    <span id="close-edit">&times;</span>
                    <?php
                    $edit_id = $_GET['edit_id'];
                    $sql = "SELECT * FROM product WHERE id = $edit_id";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    ?>
            <section id="edit-product">
                <h2>Update Product</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label for="judul">Title:</label>
                        <input type="text" id="judul" name="judul" value="<?php echo $row['judul']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Image:</label>
                        <input type="file" id="gambar" name="gambar">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo $row['judul']; ?>" style="max-width: 200px;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Description:</label>
                        <textarea id="deskripsi" name="deskripsi"><?php echo $row['deskripsi']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="harga">Price:</label>
                        <input type="number" id="harga" name="harga" step="0.01" value="<?php echo $row['harga']; ?>" required>
                    </div>
                    <button type="submit" name="submit_edit">Update Product</button>
                </form>
            </section>
        <?php endif; ?>
        
        <section id="add-product">
            <button id="add-product-btn">Add Product</button>
            <div id="add-product-form" style="display: none;">
                <h2>Add New Product</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul">Title:</label>
                        <input type="text" id="judul" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Image:</label>
                        <input type="file" id="gambar" name="gambar">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Description:</label>
                        <textarea id="deskripsi" name="deskripsi"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="harga">Price:</label>
                        <input type="number" id="harga" name="harga" step="0.01" required>
                    </div>
                    <button type="submit" name="submit_add">Add Product</button>
                </form>
            </div>
        </section>
    </main>

    <script>
         const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const closeBtn = document.querySelector('#logout-modal .close-btn');
        const addProductBtn = document.getElementById('add-product-btn');
        const addProductForm = document.getElementById('add-product-form');

        addProductBtn.addEventListener('click', function() {
            addProductForm.style.display = addProductForm.style.display === 'none' ? 'block' : 'none';
        });

        var editProduct = document.getElementById('edit-product');
        var closeEdit = document.getElementById('close-edit');

        closeEdit.addEventListener('click', function() {
            editProduct.style.display = "none";
            resetEditProduct();
        });

        window.addEventListener('click', function(event) {
            if (event.target == editProduct) {
                editProduct.style.display = "none";
                resetEditProduct();
            }
        });

        function resetEditProduct() {
            const url = new URL(window.location.href);
            url.searchParams.delete('edit_id');
            window.history.replaceState({}, '', url.toString());
            location.reload();
        }
    </script>
</body>
</html> 