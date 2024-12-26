<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productId = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Update quantity in the cart
$sql = "UPDATE unique_product_view SET quantity = '$quantity' WHERE product_id = '$productId' AND email = '" . $_SESSION['email'] . "'";
$conn->query($sql);

// Calculate the new amount
$sql = "SELECT price FROM unique_product_view WHERE product_id = '$productId' AND email = '" . $_SESSION['email'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amount = $row['price'] * $quantity;
    echo $amount;
} else {
    echo 0;
}

$conn->close();
?>
