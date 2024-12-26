<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION["email"];

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $mobile = $_POST["mobile"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $image = "";

    if (isset($_FILES["profile_photo"]) && $_FILES["profile_photo"]["error"] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

       

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["profile_photo"]["name"]);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $sql = "UPDATE users SET name='$name', age='$age', mobile='$mobile', address='$address', password='$password'";
    if (!empty($image)) {
        $sql .= ", image='$image'";
    }
    $sql .= " WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='text-align:center; margin-top:20px;'><h1>Profile Updated Successfully</h1></div>";
        echo "<script>setTimeout(function() { window.location = 'profile.php'; }, 3000);</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
