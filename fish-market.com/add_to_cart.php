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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve product_id and email from the POST request
    $product_id = $_POST['product_id'];
    $email = $_SESSION['email'];

    // Check if the product_id already exists in the cart table
    $check_stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND email = ?");
    $check_stmt->bind_param("is", $product_id, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "Product is already in the cart";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO cart (product_id, email) VALUES (?, ?)");
        $stmt->bind_param("is", $product_id, $email);

        // Execute the SQL statement
        if ($stmt->execute() === TRUE) {
            echo "Product added to cart successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
    }

    // Close the check statement and connection
    $check_stmt->close();
    $conn->close();
}
?>
