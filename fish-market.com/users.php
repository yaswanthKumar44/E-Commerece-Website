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

// Fetch data from users table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <style>
        body {
            background-image: url('https://wallpapercave.com/wp/JZGacB5.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            animation: slideInFromTop 1s;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            animation: fadeIn 1.5s;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            animation: slideInFromLeft 1s;
        }
        th {
            background-color: #f2f2f2;
            animation: slideInFromTop 1s;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            animation: fadeIn 2s;
        }
        img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        td a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
            animation: slideInFromRight 1s;
        }
        td a:hover {
            color: #0056b3;
            transform: scale(1.1);
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            animation: slideInFromBottom 1s;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            animation: fadeIn 3s;
        }
        a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInFromTop {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideInFromLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInFromRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInFromBottom {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Password</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["mobile"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "<td><img src='images/" . $row["image"] . "' alt='Profile Photo'></td>";
                    echo "<td><a href='edit_user.php?id=" . $row["id"] . "'>Edit</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No users found</td></tr>";
            }
            ?>
        </table>
        <a href="admin_home.php">Home</a>
    </div>
</body>
</html>
