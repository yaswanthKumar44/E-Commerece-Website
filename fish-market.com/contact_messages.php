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

// Fetch contact messages from database
$sql = "SELECT * FROM contact";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <style>
       body {
            background-image: url('https://imboldn.com/wp-content/uploads/2018/09/Satechi-Aluminum-Slim-Wireless-Keyboard-02-1024x768.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        @keyframes backgroundChange {
            0% { background-color: #f4f4f4; }
            25% { background-color: #e6f7ff; }
            50% { background-color: #f4f4f4; }
            75% { background-color: #ffe6f7; }
            100% { background-color: #f4f4f4; }
        }

        .table-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 2s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            animation: tableZoom 1s;
        }

        @keyframes tableZoom {
            0% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            animation: fadeInCell 1s;
        }

        @keyframes fadeInCell {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        th {
            background-color: #f4f4f4;
            animation: colorChange 3s infinite alternate;
        }

        @keyframes colorChange {
            0% { background-color: #f4f4f4; }
            50% { background-color: #e6f7ff; }
            100% { background-color: #f4f4f4; }
        }

        .no-messages {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            animation: shake 0.5s infinite;
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            50% { transform: translateX(2px); }
            75% { transform: translateX(-2px); }
            100% { transform: translateX(0); }
        }

        .home-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            animation: buttonSlideIn 1s;
        }

        .home-button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        @keyframes buttonSlideIn {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(0); }
        }

        .home-button:active {
            transform: scale(1.05);
        }

        .home-button::before {
            content: '\2190'; /* Unicode for left arrow */
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Contact Messages</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Created At</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["message"]; ?></td>
                        <td><?php echo $row["created_at"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <div class="no-messages">No contact messages found.</div>
        <?php endif; ?>
        <a href="admin_home.php" class="home-button">Home</a>
    </div>
</body>
</html>
