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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_id'])) {
    // Retrieve product_id and email from the POST request
    $product_id = $_POST['remove_product_id'];
    $email = $_SESSION['email'];

    // Prepare and bind the SQL statement to remove the product from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND email = ?");
    $stmt->bind_param("is", $product_id, $email);

    // Execute the SQL statement
    if ($stmt->execute() === TRUE) {
        echo "Product removed from cart successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
