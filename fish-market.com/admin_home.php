<?php
session_start();

// Check if email is not set in session, redirect to login.html
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

// Retrieve the logged admin's profile photo
$profile_photo = ''; // Default profile photo if none is set

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION["email"];
$sql = "SELECT profile_photo FROM admins WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profile_photo = $row["profile_photo"];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <style>
   
body {
            background-image: url('https://th.bing.com/th/id/OIP.iVzMzoYnUTc-WxTcEeO71wHaEK?rs=1&pid=ImgDetMain');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: fadeIn 2s;
            display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
        }
.container {
    position: relative;
    width: 800px;
    height: 800px;
}

.profile-card {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200px;
    height: 200px;
    margin: -100px 0 0 -100px;
    border-radius: 50%;
    background-image: url('<?php echo "images/" . $profile_photo; ?>');
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

.orbit {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 600px;
    height: 600px;
    margin: -300px 0 0 -300px;
    border-radius: 50%;
    animation: rotate 20s linear infinite;
}

.card {
    position: absolute;
    width: 150px;
    height: 150px;
    margin: 20px;
    border-radius: 10px;
    background-color: #007bff;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}

.card i {
    font-size: 24px;
    margin-bottom: 10px;
}

.card span {
    font-size: 14px;
}

.card:nth-child(1) { top: 0; left: 50%; transform: translate(-50%, -50%); }
.card:nth-child(2) { top: 14%; left: 14%; transform: translate(-50%, -50%); }
.card:nth-child(3) { top: 50%; left: 0; transform: translate(-50%, -50%); }
.card:nth-child(4) { top: 86%; left: 14%; transform: translate(-50%, -50%); }
.card:nth-child(5) { top: 100%; left: 50%; transform: translate(-50%, -50%); }
.card:nth-child(6) { top: 86%; left: 86%; transform: translate(-50%, -50%); }
.card:nth-child(7) { top: 50%; left: 100%; transform: translate(-50%, -50%); }
.card:nth-child(8) { top: 14%; left: 86%; transform: translate(-50%, -50%); }

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
    </style>
</head>
<body>
<div class="container">
    <div class="profile-card">
        <!-- The profile photo is displayed as a background image -->
    </div>
    <div class="orbit">
        <a href="admin_products.php" class="card">
            <i class="fas fa-boxes"></i>
            <span>Products</span>
        </a>
        <a href="users.php" class="card">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="add_product.html" class="card">
            <i class="fas fa-plus"></i>
            <span>Add New Product</span>
        </a>
        <a href="admin_profile.php" class="card">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="admin_register.html" class="card">
            <i class="fas fa-user-plus"></i>
            <span>Add Admin</span>
        </a>
        <a href="all_orders.php" class="card">
            <i class="fas fa-check-circle"></i>
            <span>Orders</span>
        </a>
        <a href="contact_messages.php" class="card">
            <i class="fas fa-envelope"></i>
            <span>Contact Messages</span>
        </a>
        <a href="logout.php" class="card">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
</body>
</html>
