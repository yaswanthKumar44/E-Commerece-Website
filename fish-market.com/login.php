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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if email and password are valid in users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful for user, store email in session and redirect to profile.php
        $_SESSION["email"] = $email;
        header("Location: profile.php");
        exit();
    } else {
        // Check if email and password are valid in admins table
        $stmt_admin = $conn->prepare("SELECT * FROM admins WHERE email=? AND password=?");
        $stmt_admin->bind_param("ss", $email, $password);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        if ($result_admin->num_rows > 0) {
            // Login successful for admin, store email in session and redirect to admin_home.php
            $_SESSION["email"] = $email;
            header("Location: admin_home.php");
            exit();
        } else {
            // Email or password are incorrect for both users and admins, redirect to login.html
            echo "<script>alert('Email or password are incorrect');</script>";
            echo "<script>window.location = 'login.html';</script>";
            exit();
        }
    }
}

$conn->close();
?>
