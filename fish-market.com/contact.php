<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $message = $_POST["message"];

    // Check if email is set in session
    session_start();
    if (!isset($_SESSION['email'])) {
        die("Email not found in session.");
    }

    // Use the email from session if it is set
    $email = $_SESSION['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
