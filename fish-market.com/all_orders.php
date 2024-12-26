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
    die("Connection failed: ". $conn->connect_error);
}

// Function to check if editing is allowed for a particular order
function isEditingAllowed($order_id, $conn) {
    $sql = "SELECT * FROM orders WHERE order_id = '$order_id' AND edits_allowed = 1";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Function to update order details
function updateOrderDetails($order_id, $fullName, $address, $mobileNumber, $orderStatus, $conn) {
    $sql = "UPDATE orders SET full_name = '$fullName', address = '$address', mobile_number = '$mobileNumber', order_status_new = '$orderStatus', edits_allowed = 0 WHERE order_id = '$order_id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Fetch orders based on selected statuses
$selected_statuses = isset($_POST['order_status']) ? $_POST['order_status'] : ['confirmed'];
$status_placeholders = implode(',', array_fill(0, count($selected_statuses), '?'));
$sql = "SELECT * FROM orders WHERE order_status_new IN ($status_placeholders) ORDER BY order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($selected_statuses)), ...$selected_statuses);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>
       body {
            background-image: url('https://wallpaperaccess.com/full/6977.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        .header, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            animation: slideInDown 1s;
        }
        .container {
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            animation: fadeIn 2s;
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
        }
        .order-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-table th, .order-table td, .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            animation: fadeInUp 1s;
        }
        .order-table th, .items-table th {
            background-color: #f2f2f2;
        }
        .order-table tr:hover, .items-table tr:hover {
            background-color: #f9f9f9;
        }
        .edit-form {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            animation: fadeIn 1s;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            animation: zoomIn 1s;
        }
        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        img {
            border-radius: 5px;
            transition: transform 0.3s;
            animation: zoomIn 1s;
        }
        img:hover {
            transform: scale(1.1);
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        @keyframes slideInDown {
            from {transform: translateY(-100%);}
            to {transform: translateY(0);}
        }
        @keyframes fadeInUp {
            from {transform: translateY(20px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
        @keyframes zoomIn {
            from {transform: scale(0);}
            to {transform: scale(1);}
        }
        .home-button {
            padding: 10px 15px;
            background: #898989;;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
            animation: zoomIn 1s;
        }
        .home-button:hover {
            background-color: #555;
            transform: scale(1.05);
        }
    </style>
<body>
    <div class="header">
        <h1>All Orders for Admin</h1>
        <a href="admin_home.php" class="home-button">Home</a>
    </div>

    <div class="container">
        <h2>Filter Orders</h2>
        <form method="post" action="">
            <label for="order_status">Select Order Status:</label><br>
            <select name="order_status[]" id="order_status" multiple>
                <option value="confirmed" <?php echo in_array('confirmed', $selected_statuses) ? 'selected' : ''; ?>>Confirmed</option>
                <option value="completed" <?php echo in_array('completed', $selected_statuses) ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo in_array('cancelled', $selected_statuses) ? 'selected' : ''; ?>>Cancelled</option>
            </select><br><br>
            <button type="submit">Filter Orders</button>
        </form>

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
                    echo "<div class='order-card'>";
                    echo "<h3>Order #". $row['order_id']. "</h3>";
                    echo "<table class='order-table'>";
                    echo "<tr><th>Order ID</th><td>". $row['order_id']. "</td></tr>";
                    echo "<tr><th>Full Name</th><td>". $row['full_name']. "</td></tr>";
                    echo "<tr><th>Address</th><td>". $row['address']. "</td></tr>";
                    echo "<tr><th>Mobile Number</th><td>". $row['mobile_number']. "</td></tr>";
                    echo "<tr><th>Order Date</th><td>". $row['order_date']. "</td></tr>";
                    echo "<tr><th>Order Status</th><td>". $row['order_status_new']. "</td></tr>";
                    echo "</table>";

                    // Check if editing is allowed for this order
                    if (isEditingAllowed($row['order_id'], $conn)) {
                        echo "<button onclick=\"toggleEditForm('edit_form_". $row['order_id']. "')\">Edit Details</button>";
                        echo "<form id='edit_form_". $row['order_id']. "' class='edit-form' action='edit_order.php' method='post'>";
                        echo "<input type='hidden' name='order_id' value='". $row['order_id']. "'>";
                        echo "<label>Full Name:</label><br>";
                        echo "<input type='text' name='full_name' value='". $row['full_name']. "' required><br>";
                        echo "<label>Address:</label><br>";
                        echo "<textarea name='address' required>". $row['address']. "</textarea><br>";
                        echo "<label>Mobile Number:</label><br>";
                        echo "<input type='text' name='mobile_number' value='". $row['mobile_number']. "' required><br>";
                        echo "<label>Order Status:</label><br>";
                        echo "<select name='order_status' required>";
                        echo "<option value='confirmed'". ($row['order_status_new'] == 'confirmed' ? ' selected' : '') .">Confirmed</option>";
                        echo "<option value='completed'". ($row['order_status_new'] == 'completed' ? ' selected' : '') .">Completed</option>";
                        echo "<option value='cancelled'". ($row['order_status_new'] == 'cancelled' ? ' selected' : '') .">Cancelled</option>";
                        echo "</select><br>";
                        echo "<button type='submit'>Update Details</button>";
                        echo "</form>";
                    }

                    $total_amount = 0;

                    echo "<h3>Items in Order #". $order_id. "</h3>";
                    echo "<table class='items-table'>";
                    echo "<tr><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th></tr>";

                    while ($item_row = $item_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='images/". $item_row['image']. "' alt='". $item_row['name']. "' width='50'></td>";
                        echo "<td>". $item_row['name']. "</td>";
                        echo "<td>". $item_row['description']. "</td>";
                        echo "<td>". $item_row['price']. "</td>";
                        echo "<td>". $item_row['quantity']. "</td>";
                        echo "</tr>";

                        $total_amount += $item_row['price'] * $item_row['quantity'];
                    }

                    echo "<tr><th colspan='4'>Total Amount</th><td>". $total_amount. "</td></tr>";
                    echo "</table>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>No orders found.</p>";
        }
        $conn->close();
        ?>
    </div>

    <div class="footer">
        <p>&copy; 2024 Fish Market. All rights reserved.</p>
    </div>

    <script>
        function toggleEditForm(formId) {
            var form = document.getElementById(formId);
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</body>
</html>
