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

// Fetch orders for the logged-in user based on the selected status
$email = $_SESSION['email'];
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

$sql = "SELECT * FROM orders WHERE email = '$email'";
if ($status !== 'all') {
    $sql .= " AND order_status_new = '$status'";
}
$sql .= " ORDER BY order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>
        body {
            background-image: url('https://wallpaperim.net/_data/i/upload/2014/09/16/20140916208241-ed1627dc-me.jpg');
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
        }
        .nav {
            display: flex;
            justify-content: center;
            background-color: #444;
            flex-wrap: wrap;
        }
        .nav a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: #555;
            animation: shake 0.5s;
        }
        .container {
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            animation: fadeIn 1s;
        }
        .order-card {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            animation: fadeIn 1s;
        }
        .order-card h3 {
            margin-top: 0;
            animation: slideInFromLeft 1s;
        }
        .order-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            animation: slideInFromRight 1s;
        }
        .order-table th, .order-table td, .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-table th, .items-table th {
            background-color: #f2f2f2;
            animation: scaleIn 1s;
        }
        .order-table tr:hover, .items-table tr:hover {
            background-color: #f9f9f9;
            animation: rotateIn 1s;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            animation: bounceIn 1s;
        }
        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
            animation: flipIn 1s;
        }
        img {
            border-radius: 100px;
            transition: transform 0.3s;
            animation: slideInFromTop 1s;
        }
        img:hover {
            transform: scale(1.1);
            animation: slideInFromBottom 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInFromLeft {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideInFromRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideInFromTop {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        @keyframes slideInFromBottom {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
      
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        @keyframes fadeInAndScale {
            from { opacity: 0; transform: scale(0); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes bounceIn {
            from { transform: scale(0.5); }
            to { transform: scale(1); }
        }
        @keyframes flipIn {
            from { transform: rotateY(-90deg); }
            to { transform: rotateY(0); }
        }
        @keyframes bounceAndRotateIn {
            0% { transform: translateY(-100%) rotate(0); }
            50% { transform: translateY(0) rotate(360deg); }
            100% { transform: translateY(-100%) rotate(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        @keyframes swing {
            20% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }
        @keyframes wobble {
            0% { transform: translateX(0%); }
            15% { transform: translateX(-25%) rotate(-5deg); }
            30% { transform: translateX(20%) rotate(3deg); }
            45% { transform: translateX(-15%) rotate(-3deg); }
            60% { transform: translateX(10%) rotate(2deg); }
            75% { transform: translateX(-5%) rotate(-1deg); }
            100% { transform: translateX(0%) rotate(0deg); }
        }
        @keyframes jello {
            0%, 11.1%, 100% { transform: translate3d(0, 0, 0); }
            22.2% { transform: skewX(-12.5deg) skewY(-12.5deg); }
            33.3% { transform: skewX(6.25deg) skewY(6.25deg); }
            44.4% { transform: skewX(-3.125deg) skewY(-3.125deg); }
            55.5% { transform: skewX(1.5625deg) skewY(1.5625deg); }
            66.6% { transform: skewX(-0.78125deg) skewY(-0.78125deg); }
            77.7% { transform: skewX(0.390625deg) skewY(0.390625deg); }
            88.8% { transform: skewX(-0.1953125deg) skewY(-0.1953125deg); }
        }
        @keyframes flash {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes rubberBand {
            0% { transform: scale(1); }
            30% { transform: scale(1.25, 0.75); }
            40% { transform: scale(0.75, 1.25); }
            50% { transform: scale(1.15, 0.85); }
            65% { transform: scale(0.95, 1.05); }
            75% { transform: scale(1.05, 0.95); }
            100% { transform: scale(1); }
        }
        @keyframes tada {
            0% { transform: scale(1); }
            10%, 20% { transform: scale(0.9) rotate(-3deg); }
            30%, 50%, 70%, 90% { transform: scale(1.1) rotate(3deg); }
            40%, 60%, 80% { transform: scale(1.1) rotate(-3deg); }
            100% { transform: scale(1) rotate(0); }
        }

        /* Highlight active link */
        .nav a.active {
            background-color: #007bff;
        }

        /* Separate animation for highlighting active link */
        .nav a.active {
            animation: fadeInAndScale 0.5s ease-in-out;
        }

        /* Add this to the existing CSS */
        .button-group a:hover button {
            animation: pulse 0.5s infinite;
        }
        .footer {
            animation: flash 1.5s infinite;
        }
        .order-card:hover {
            animation: swing 1s;
        }
        .order-table th, .items-table th {
            animation: tada 1s;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>Your Orders</h1>
        <div class="button-group">
            <a href="?status=all"><button>All Orders</button></a>
            <a href="?status=completed"><button>Orders Completed</button></a>
            <a href="?status=cancelled"><button>Orders Cancelled</button></a>
            <a href="?status=confirmed"><button>Orders Confirmed</button></a>
        </div>
    </div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="orders.php" class="active">Orders</a>
        <a href="profile.php">Profile</a>
        <a href="contact.html">Contact</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container" id="order-list">
        <h2>Order History</h2>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Fetch ordered items for this order and join with products table to get product details
                $order_id = $row['order_id'];
                $item_sql = "
                    SELECT oi.quantity, p.name, p.description, p.price, p.image 
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = '$order_id'
                ";
                $item_result = $conn->query($item_sql);
                
                // Check if there are items in the order
                if ($item_result->num_rows > 0) {
                    echo "<div class='order-card' data-status='confirmed'>";
                    echo "<h3>Order #" . $row['order_id'] . "</h3>";
                    echo "<table class='order-table'>";
                    echo "<tr><th>Order ID</th><td>" . $row['order_id'] . "</td></tr>";
                    echo "<tr><th>Full Name</th><td>" . $row['full_name'] . "</td></tr>";
                    echo "<tr><th>Address</th><td>" . $row['address'] . "</td></tr>";
                    echo "<tr><th>Mobile Number</th><td>" . $row['mobile_number'] . "</td></tr>";
                    echo "<tr><th>Order Date</th><td>" . $row['order_date'] . "</td></tr>";
                    echo "<tr><th>Order Status</th><td>" . $row['order_status_new'] . "</td></tr>";
                    echo "</table>";

                    $total_amount = 0;

                    echo "<h3>Items in Order #" . $order_id . "</h3>";
                    echo "<table class='items-table'>";
                    echo "<tr><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Amount</th></tr>";
                    while($item_row = $item_result->fetch_assoc()) {
                        $amount = $item_row['quantity'] * $item_row['price'];
                        $total_amount += $amount;
                        echo "<tr>";
                        echo "<td><img src='images/" . $item_row['image'] . "' alt='" . $item_row['name'] . "' style='width:100px;height:100px;'></td>";
                        echo "<td>" . $item_row['name'] . "</td>";
                        echo "<td>" . $item_row['description'] . "</td>";
                        echo "<td>$" . $item_row['price'] . "</td>";
                        echo "<td>" . $item_row['quantity'] . "</td>";
                        echo "<td>$" . $amount . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<h3>Total Amount for Order #" . $order_id . ": $" . $total_amount . "</h3>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>No orders found.</p>";
        }
        ?>
    </div>
    <div class="footer">
        <p>&copy; 2024 Your Website Name. All rights reserved.</p>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the connection at the end
?>
