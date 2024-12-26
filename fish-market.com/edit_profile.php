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

// Update admin data in database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST["name"];
    $newMobile = $_POST["mobile"];

    // Handle file upload if a new profile photo is uploaded
    if (!empty($_FILES["profile_photo"]["name"])) {
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFile);
        $newProfilePhoto = basename($_FILES["profile_photo"]["name"]);
    } else {
        $newProfilePhoto = $profilePhoto;
    }

    $updateSql = "UPDATE admins SET name='$newName', mobile='$newMobile', profile_photo='$newProfilePhoto' WHERE email='$email'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Profile updated successfully";
        header("Location: admin_profile.php");
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
            background-image: url('https://rare-gallery.com/uploads/posts/1230800-abstract-light-tech.jpg');
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

        .profile-group input[type="text"],
        .profile-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .profile-group button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .profile-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="profile-group">
                <label>Email:</label>
                <span><?php echo $email; ?></span>
            </div>
            <div class="profile-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="profile-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile" value="<?php echo $mobile; ?>" required>
            </div>
            <div class="profile-group">
                <label>Profile Photo:</label>
                <input type="file" name="profile_photo">
                <img src="images/<?php echo $profilePhoto; ?>" alt="Profile Photo" width="100">
            </div>
            <div class="profile-group">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
