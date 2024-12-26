<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["email"])) {
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

// Fetch admin data from database
$email = $_SESSION["email"];
$sql = "SELECT * FROM admins WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $mobile = $row["mobile"];
    $profilePhoto = $row["profile_photo"];
} else {
    echo "Admin not found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            animation: backgroundAnimation 20s infinite alternate;
            background-image: url('https://wallpapertag.com/wallpaper/full/c/b/c/149186-top-graphic-background-1920x1200.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }


        @keyframes backgroundAnimation {
            0% {
                background-color: #f4f4f4;
            }
            50% {
                background-color: #e6f7ff;
            }
            100% {
                background-color: #f4f4f4;
            }
        }

        .profile-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            animation: cardAnimation 2s ease-in-out;
        }

        @keyframes cardAnimation {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-group {
            margin-bottom: 15px;
            color: #555;
        }

        .profile-group label {
            display: block;
            margin-bottom: 5px;
        }

        .profile-group span {
            font-weight: bold;
            color: #007bff;
        }

        .profile-group img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            border: 3px solid #007bff;
            transition: transform 0.3s ease-in-out;
        }

        .profile-group img:hover {
            transform: scale(1.1);
        }

        .profile-group a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .profile-group a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Admin Profile</h2>
        <div class="profile-group">
            <img src="images/<?php echo $profilePhoto; ?>" alt="Profile Photo">
        </div>
        <div class="profile-group">
            <label>Email:</label>
            <span><?php echo $email; ?></span>
        </div>
        <div class="profile-group">
            <label>Name:</label>
            <span><?php echo $name; ?></span>
        </div>
        <div class="profile-group">
            <label>Mobile Number:</label>
            <span><?php echo $mobile; ?></span>
        </div>
        <div class="profile-group">
            <a href="admin_home.php">Home</a>
        </div>
        <div class="profile-group">
            <a href="edit_profile.php">Edit Profile</a>
        </div>
        <div class="profile-group">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
