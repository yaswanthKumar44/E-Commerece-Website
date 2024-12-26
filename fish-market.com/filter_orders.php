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

// Fetch orders for the logged-in user based on the provided status
$email = $_SESSION['email'];
$status = $_GET['status'];
$sql = "SELECT * FROM orders WHERE email = '$email' AND order_status_new = '$status' ORDER BY order_date DESC";
$result = $conn->query($sql);

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
            echo "<div class='order-card'>";
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
                echo "<td><img src='images/" . $item_row['image'] . "' alt='" . $item_row['name'] . "' style='width:50px;height:50px;'></td>";
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
$conn->close();
?>
