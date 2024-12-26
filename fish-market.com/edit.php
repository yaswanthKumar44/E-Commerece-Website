<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            background-image: url('https://www.mashed.com/img/gallery/best-and-worst-grocery-stores-to-buy-fish/l-intro-1672069485.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
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

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $age = $row["age"];
            $mobile = $row["mobile"];
            $address = $row["address"];
            $profile_photo = "images/" . $row["image"];
            $password = $row["password"];

            echo "<form action='update.php' method='post' enctype='multipart/form-data'>";

            echo "<div class='form-group'>";
            echo "<label for='name'>Name:</label>";
            echo "<input type='text' id='name' name='name' value='$name' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='email'>Email:</label>";
            echo "<input type='email' id='email' name='email' value='$email' readonly>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='age'>Age:</label>";
            echo "<input type='number' id='age' name='age' value='$age' min='18' max='100' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='mobile'>Mobile:</label>";
            echo "<input type='tel' id='mobile' name='mobile' value='$mobile' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='address'>Address:</label>";
            echo "<textarea id='address' name='address' rows='4' required>$address</textarea>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='password'>Password:</label>";
            echo "<input type='password' id='password' name='password' value='$password' required>";

            echo "<input type='checkbox' id='showPassword' onclick='togglePasswordVisibility()'>";
            echo "<label for='showPassword'>Show Password</label>";

            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label for='profile_photo'>Profile Photo:</label>";
            echo "<img src='$profile_photo' alt='Profile Photo' style='width: 100px; height: 100px; border-radius: 50%;'>";
            echo "<input type='file' id='profile_photo' name='profile_photo'>";
            echo "</div>";

            echo "<button type='submit' name='submit'>Update</button>";
            echo "</form>";
        } else {
            echo "User not found";
        }

        $conn->close();
        ?>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>
