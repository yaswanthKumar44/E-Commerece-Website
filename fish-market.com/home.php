<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
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
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fish Store</title>
    <style>

@media (max-width: 768px) {
            .products {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .products {
                grid-template-columns: 1fr;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        @keyframes bounceIn {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-30px); }
            60% { transform: translateY(-15px); }
        }
        @keyframes zoomIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes flipIn {
            from { transform: rotateY(-90deg); opacity: 0; }
            to { transform: rotateY(0); opacity: 1; }
        }
        @keyframes lightSpeedIn {
            from { transform: translateX(100%) skewX(-30deg); opacity: 0; }
            to { transform: translateX(0) skewX(0); opacity: 1; }
        }
        @keyframes rotateIn {
            from { transform: rotate(-200deg); opacity: 0; }
            to { transform: rotate(0); opacity: 1; }
        }
        @keyframes swing {
            20% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }
        @keyframes rubberBand {
            0%, 100% { transform: scaleX(1); }
            30% { transform: scaleX(1.25); }
            40% { transform: scaleX(0.75); }
            50% { transform: scaleX(1.15); }
            65% { transform: scaleX(0.95); }
            75% { transform: scaleX(1.05); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            background-image: url('https://wallpapercave.com/wp/wp7133980.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: fadeIn 2s;
        }
        .header, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            animation: slideIn 1s;
        }
        .nav {
            display: flex;
            justify-content: center;
            background-color: #444;
            flex-wrap: wrap;
            animation: bounceIn 2s;
        }
        .nav a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: #555;
            animation: pulse 1s;
        }
        .container {
            padding: 20px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            animation: zoomIn 1s;
        }
        h2 {
            text-align: center;
            color: #333;
            animation: lightSpeedIn 2s;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeInUp 2s;
        }
        .product {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            width: 250px;
            box-sizing: border-box;
            text-align: center;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            animation: flipIn 2s;
        }
        .product:hover {
            transform: scale(1.05);
            animation: rubberBand 1s;
        }
        .product img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            animation: rotateIn 2s;
        }
        .product h3 {
            margin: 10px 0;
            color: #333;
            animation: swing 2s;
        }
        .product p {
            color: #777;
            font-size: 14px;
        }
        .product button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            animation: pulse 1s;
        }
        .product button:hover {
            background-color: #218838;
        }
        @media (max-width: 768px) {
            .product {
                width: calc(50% - 40px);
            }
        }
        @media (max-width: 480px) {
            .product {
                width: calc(100% - 40px);
            }
        }
        .nav .active {
            background-color: #007bff;
            animation: pulse 1s infinite;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Welcome to Our Fish Store</h1>
    </div>
    <div class="nav">
        <a href="home.php" class="active">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="orders.php">Orders</a>
        <a href="profile.php">Profile</a>
        <a href="contact.html">Contact</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Products</h2>
        <div class="products">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            echo "<p>Available Quantity: " . $row['available_quantity'] . " KG </p>";
            echo "<button onclick='addToCart(" . $row['product_id'] . ")'>Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No products available</p>";
    }
    ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Fish Store. All rights reserved.</p>
    </div>

    <script>
        function addToCart(productId) {
            var email = "<?php echo $_SESSION['email']; ?>";

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                }
            };
            xhttp.open("POST", "add_to_cart.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("product_id=" + productId + "&email=" + email);
        }
    </script>
</body>
</html>
