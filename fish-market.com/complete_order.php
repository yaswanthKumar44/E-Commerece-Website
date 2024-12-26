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

$email = $_SESSION['email'];
$fullName = $_POST['full_name'];
$address = $_POST['address'];
$mobileNumber = $_POST['mobile_number'];
$productIds = $_POST['product_id'];
$quantities = $_POST['quantity'];

$outOfStockProducts = [];
$successfullyOrderedProducts = [];

// Insert order into orders table
$sql = "INSERT INTO orders (email, full_name, address, mobile_number, order_date) VALUES ('$email', '$fullName', '$address', '$mobileNumber', NOW())";

if ($conn->query($sql) === TRUE) {
    $orderId = $conn->insert_id;

    // Check availability and insert each product into order_items table
    for ($i = 0; $i < count($productIds); $i++) {
        $productId = $productIds[$i];
        $quantity = $quantities[$i];

        // Check if the product is available in sufficient quantity
        $checkProductSql = "SELECT available_quantity, name FROM products WHERE product_id = '$productId'";
        $result = $conn->query($checkProductSql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $availableQuantity = $row['available_quantity'];
            $productName = $row['name'];

            if ($availableQuantity >= $quantity) {
                $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$orderId', '$productId', '$quantity')";
                $conn->query($sql);

                // Remove product from cart
                $removeCartSql = "DELETE FROM cart WHERE email='$email' AND product_id='$productId'";
                $conn->query($removeCartSql);

                // Reduce quantity in products table
                $updateProductSql = "UPDATE products SET available_quantity = available_quantity - '$quantity' WHERE product_id = '$productId'";
                $conn->query($updateProductSql);

                // Add to successfully ordered products array
                $successfullyOrderedProducts[] = $productName;
            } else {
                $outOfStockProducts[] = $productName;
            }
        }
    }

   ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 2s;
        }

       .container {
            padding: 20px;
            animation: fadeInUp 1.5s;
        }

       .success-message {
            background-color: #C6F7D0; /* Light green background color */
            padding: 10px;
            border-radius: 5px;
            animation: fadeIn 1s;
        }

       .error-message {
            background-color: #FFC5C5; /* Light red background color */
            padding: 10px;
            border-radius: 5px;
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .highlight {
        background-color: #34C759; /* Green color */
        color: #FFFFFF; /* White text color */
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .highlight:hover {
        background-color: #007bff; /* Blue color */
    }

    .highlight:active {
        background-color: #34C759; /* Green color */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    </style>

    <div class="container">
        <?php if (!empty($successfullyOrderedProducts)) {?>
            <div class="success-message">
                <p>Order placed successfully for the following items: <?php echo implode(", ", $successfullyOrderedProducts);?>.</p>
            </div>
        <?php }?>

        <?php if (!empty($outOfStockProducts)) {?>
            <div class="error-message">
                <p>The following items are out of stock and were not added to your order: <?php echo implode(", ", $outOfStockProducts);?>. Please review your cart and try again.</p>
            </div>
        <?php }?>

        <form action="orders.php" method="get">
    <button type="submit" class="btn btn-primary highlight">Go to Orders</button>
</form>
    </div>

    <?php
} else {
    echo "Error: ". $sql. "<br>". $conn->error;
}

$conn->close();
?>