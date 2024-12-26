<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            animation: fadeIn 1s;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
            animation: fadeIn 1s;
        }
        .message.success {
            background-color: #4caf50;
            color: white;
        }
        .message.error {
            background-color: #f44336;
            color: white;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
            animation: bounceIn 1s;
        }
        .button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        form {
            display: flex;
            flex-direction: column;
            animation: slideIn 1s;
        }
        form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            animation: fadeIn 1s;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="file"],
        form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            animation: inputSlideIn 1s;
        }
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            animation: buttonSlideIn 1s;
        }
        form input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes inputSlideIn {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes buttonSlideIn {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
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

        // Process registration form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $mobile = $_POST['mobile'];
            $profilePhoto = $_FILES['profile_photo']['name'];
            $targetDir = "images/";
            $targetFile = $targetDir . basename($profilePhoto);

            // Check if passcode is correct
            $passcode = $_POST['passcode'];
            if ($passcode !== 'INFINITY WAR') {
                echo "<div class='message error'>Invalid passcode</div>";
                exit();
            }

            // Check if email already exists in users table
            $checkUserEmailQuery = "SELECT * FROM users WHERE email=?";
            $stmt = $conn->prepare($checkUserEmailQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultUser = $stmt->get_result();

            // Check if email already exists in admins table
            $checkAdminEmailQuery = "SELECT * FROM admins WHERE email=?";
            $stmt = $conn->prepare($checkAdminEmailQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultAdmin = $stmt->get_result();

            if ($resultUser->num_rows > 0 || $resultAdmin->num_rows > 0) {
                echo "<div class='message error'>Email already in use</div>";
                exit();
            }

            // Check file type
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array($imageFileType, $allowedTypes)) {
                echo "<div class='message error'>Only JPG, JPEG, PNG & GIF files are allowed.</div>";
                exit();
            }

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
                // Insert admin data into database
                $insertQuery = "INSERT INTO admins (email, password, name, mobile, profile_photo) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("sssss", $email, $password, $name, $mobile, $profilePhoto);
                if ($stmt->execute()) {
                    echo "<div class='message success'>Admin registered successfully</div>";
                    echo '<a href="login.html" class="button">Go to Login Page</a>';
                    echo '<a href="admin_home.php" class="button">Go to Home Page</a>';
                } else {
                    echo "<div class='message error'>Error: " . $stmt->error . "</div>";
                }
            } else {
                echo "<div class='message error'>Failed to upload profile photo</div>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
        ?>

        
    </div>
</body>
</html>
