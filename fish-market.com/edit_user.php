<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chaitu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data based on ID
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Update user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $age = $_POST["age"];
    $mobile = $_POST["mobile"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    
    // File upload handling
    $image = $_FILES["image"]["name"];
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File uploaded successfully
    } else {
        $image = $user["image"];
    }

    $sql = "UPDATE users SET name='$name', email='$email', age='$age', mobile='$mobile', address='$address', password='$password', image='$image' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: users.php"); // Redirect to users page after update
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            border: none;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>" required>

            <label for="mobile">Mobile</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo $user['mobile']; ?>" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" required><?php echo $user['address']; ?></textarea>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?php echo $user['password']; ?>" required>

            <label for="image">Profile Photo</label>
            <input type="file" id="image" name="image">

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
