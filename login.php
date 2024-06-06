<?php
// Mulai sesi untuk mengakses variabel sesi
session_start();

// Ambil data dari form login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'blog_database');

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Escape string untuk mencegah SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Query untuk memeriksa login admin
    $query = "SELECT * FROM login WHERE username = '$username' AND password = '$password' AND username IN ('admin1', 'admin2', 'admin3')";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Login berhasil, set session
        $_SESSION['username'] = $username;

        // Redirect ke halaman adm_index.php
        header("Location: adm_index.php");
        exit();
    } else {
        echo "Username atau password salah.";
    }

    // Tutup koneksi
    $conn->close();
} 
?>

<!DOCTYPE html>
<html lang="en">
<body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <script src="script.js" defer></script>
</head>

<main>
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
    <div class="lebel-login">
        <h1>Admin Login</h1>
    <div class="login-container">
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="username"><p>Username:</p></label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password"><p>Password:</p></label>
                <input type="password" name="password" id="password" required>
            </div>
            <input type="submit" value="Login" class="btn">
        </form>
    </div>
</body>
</html>