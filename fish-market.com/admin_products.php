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

// Delete product if delete button is clicked
if (isset($_POST['delete'])) {
    $product_id = $_POST['delete'];
    $sql_delete = "DELETE FROM products WHERE product_id='$product_id'";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Product deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting product');</script>";
    }
}

// Edit product if edit form is submitted
if (isset($_POST['edit_id'])) {
    $product_id = $_POST['edit_id'];
    $new_name = $_POST['new_name'];
    $new_description = $_POST['new_description'];
    $new_price = $_POST['new_price'];
    $new_quantity = $_POST['new_quantity'];

    $sql_update = "UPDATE products SET name='$new_name', description='$new_description', price='$new_price', available_quantity='$new_quantity'";

    if ($_FILES['new_image']['name']) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["new_image"]["name"]);
        move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file);
        $sql_update .= ", image='" . basename($_FILES["new_image"]["name"]) . "'";
    }

    $sql_update .= " WHERE product_id='$product_id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Product updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating product');</script>";
    }
}

// Fetch data from products table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
        body {
            background-image: url('https://external-preview.redd.it/wKq8c-GFtjkwxQnf0QeOJOUz9oCXRaCvm1Ppl1Z_6KY.jpg?auto=webp&s=17efa08f085d3f5fe662f2d827e4a4a9078d7b61');
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
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }
        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            animation: slideIn 1s ease-in-out;
        }
        .product-card .delete-btn, .product-card .edit-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            animation: pulse 1.5s infinite;
        }
        .product-card .edit-btn {
            background-color: #007bff;
            right: 70px;
        }
        .edit-form {
            display: none;
            padding: 10px;
            margin-top: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .edit-form input[type="text"],
        .edit-form input[type="number"],
        .edit-form input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .edit-form button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .edit-form button[type="submit"]:hover {
            background-color: #218838;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out;
        }
        img:hover {
            transform: scale(1.1);
        }
        .home-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .home-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Products</h2>
    <a href="admin_home.php" class="home-btn">Click To Go Home</a>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<h3>" . $row["name"] . "</h3>";
            echo "<img src='images/" . $row["image"] . "' alt='Product Image'>";
            echo "<p><strong>Description:</strong> " . $row["description"] . "</p>";
            echo "<p><strong>Price:</strong> $" . $row["price"] . "</p>";
            echo "<p><strong>Available Quantity:</strong> " . $row["available_quantity"] . "</p>";
            echo "<button class='edit-btn' onclick='toggleEditForm(" . $row["product_id"] . ")'>Edit</button>";
            echo "<button class='delete-btn' onclick='confirmDelete(" . $row["product_id"] . ")'>Delete</button>";
            echo "<form id='editForm" . $row["product_id"] . "' class='edit-form' method='POST' enctype='multipart/form-data' onsubmit='return confirm(\"Are you sure you want to update this product?\")'>";
            echo "<input type='text' name='new_name' placeholder='New Name' value='" . $row["name"] . "'>";
            echo "<input type='text' name='new_description' placeholder='New Description' value='" . $row["description"] . "'>";
            echo "<input type='number' name='new_price' placeholder='New Price' value='" . $row["price"] . "'>";
            echo "<input type='number' name='new_quantity' placeholder='New Quantity' value='" . $row["available_quantity"] . "'>";
            echo "<input type='file' name='new_image'>";
            echo "<input type='hidden' name='edit_id' value='" . $row["product_id"] . "'>";
            echo "<button type='submit'>Save</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found</p>";
    }
    ?>
    

    <script>
        function toggleEditForm(id) {
            var form = document.getElementById('editForm' + id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
                form.style.animation = 'fadeIn 0.5s ease-in-out';
            } else {
                form.style.animation = 'fadeOut 0.5s ease-in-out';
                setTimeout(function() {
                    form.style.display = 'none';
                }, 500);
            }
        }

        function confirmDelete(id) {
            var confirmDelete = confirm("Are you sure you want to delete this product?");
            if (confirmDelete) {
                // Submit the form
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin_products.php';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete';
                input.value = id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                // Do nothing
            }
        }
    </script>
</body>
</html>
