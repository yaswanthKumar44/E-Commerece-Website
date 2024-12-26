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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);
    $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));

    if (!is_dir("images")) {
        mkdir("images");
    }

    // Check if file is not a video
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='register.html';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result_email = $conn->query($check_email);
    if ($result_email->num_rows > 0) {
        echo "<script>alert('Email already exists'); window.location.href='register.html';</script>";
        exit();
    }

    // Upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, age, mobile, address, password, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissss", $name, $email, $age, $mobile, $address, $password, $image);
        if ($stmt->execute()) {
    echo "<div id='message' class='fade-in'>Registered successfully. Redirecting to login page...</div>";
    echo "<script>setTimeout(function(){ window.location.href='login.html'; }, 3000);</script>";
}

         else {
            echo "Error inserting data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to upload image";
    }
}

$conn->close();
?>

