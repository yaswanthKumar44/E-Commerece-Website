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

// Function to check if editing is allowed for a particular order
function isEditingAllowed($order_id, $conn) {
    $sql = "SELECT * FROM orders WHERE order_id = ? AND edits_allowed = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to update order details
function updateOrderDetails($order_id, $fullName, $address, $mobileNumber, $orderStatus, $conn) {
    $sql = "UPDATE orders SET full_name = ?, address = ?, mobile_number = ?, order_status_new = ?, edits_allowed = 0 WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fullName, $address, $mobileNumber, $orderStatus, $order_id);
    return $stmt->execute();
}

// Handle form submission to update order details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $order_status = $_POST['order_status'];

    if (updateOrderDetails($order_id, $full_name, $address, $mobile_number, $order_status, $conn)) {
        header("Location: all_orders.php");
        exit();
    } else {
        echo "Error updating order details.";
    }
}

$conn->close();
?>
