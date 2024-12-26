<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if email is set in session
if (!isset($_SESSION["email"]) || empty($_SESSION["email"])) {
    // Redirect to login page if email is not set or empty
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

$email = $_SESSION["email"];

// Query to get user information based on email
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display user information
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $age = $row["age"];
    $mobile = $row["mobile"];
    $address = $row["address"];
    $profile_photo = "images/". $row["image"];
} else {
    $error_message = "User not found";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes growIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes rotateIn {
            from { transform: rotate(-180deg); opacity: 0; }
            to { transform: rotate(0); opacity: 1; }
        }
        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%, 75% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        @keyframes colorChange {
            0% { background-color: #007bff; }
            25% { background-color: #ff69b4; }
            50% { background-color: #33cc33; }
            75% { background-color: #ffff66; }
            100% { background-color: #007bff; }
        }

        body {
            background-image: url('https://www.mashed.com/img/gallery/best-and-worst-grocery-stores-to-buy-fish/l-intro-1672069485.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
       .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            animation: growIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            animation: slideIn 1s ease-in-out;
        }
        p {
            margin-bottom: 10px;
            animation: slideInLeft 1s ease-in-out;
        }
        img {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            max-width: 200px;
            animation: bounceIn 1s ease-in-out;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
            animation: slideInRight 1s ease-in-out;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            animation: pulse 1s infinite;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .header, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            animation: heartbeat 1s infinite;
        }
        .nav {
            display: flex;
            justify-content: center;
            background-color: #444;
            flex-wrap: wrap;
            animation: slideIn 1s ease-in-out;
        }
        .nav a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s;
            animation: rotateIn 1s ease-in-out;
        }
        .nav a:hover {
            background-color: #555;
            animation: shake 0.5s;
        }
        .nav .active {
            background-color: #007bff;
        }
        .color-changing-effect {
            animation: colorChange 10s infinite;
        }
    </style>
</head>
<body>
    <div class="header color-changing-effect">
        <h1>Welcome to Our Fish Store</h1>
    </div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="orders.php">Orders</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="contact.html">Contact</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <?php if (isset($error_message)) {
            echo "<p>$error_message</p>";
        } else { ?>
            <h2>User Details</h2>
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?></p>
            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($address)); ?></p>
            <div class='button-container'>
                <a href='edit.php' class='button'>Edit Details</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>