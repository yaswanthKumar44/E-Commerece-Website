<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown Animation</title>
    <style>
        @keyframes countdown {
            0% {
                transform: scaleX(1);
                background-color: #28a745;
            }
            50% {
                transform: scaleX(0.5);
                background-color: #ffc107;
            }
            100% {
                transform: scaleX(0);
                background-color: #dc3545;
            }
        }

        #countdown {
            width: 100%;
            height: 5px;
            background-color: #28a745;
            animation: countdown 3s linear;
        }
    </style>
</head>
<body>
    <div id="countdown"></div>
</body>
</html>

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $available_quantity = intval($_POST['available_quantity']);
    $image = $_FILES['image']['name'];
    $target = "images/".basename($image);

    if (!is_dir("images")) {
        mkdir("images");
    }

    // Upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, available_quantity, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $name, $description, $price, $available_quantity, $image);
        if ($stmt->execute()) {
            echo "Product added successfully. Redirecting to Admin home page...";
            header("Refresh: 3; URL=admin_home.php"); // Redirect to home page after 3 seconds
        } else {
            echo "Error inserting data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image";
    }
}

$conn->close();
?>
