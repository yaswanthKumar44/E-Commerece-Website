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

// Initialize variables
$name = $description = $price = $available_quantity = $product_id = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $available_quantity = intval($_POST['available_quantity']);

    // Update data in database
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, available_quantity = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $available_quantity, $product_id);
    if ($stmt->execute()) {
        echo "Product updated successfully. Redirecting to home page...";
        header("Refresh: 3; URL=home.php"); // Redirect to home page after 3 seconds
    } else {
        echo "Error updating product: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch product details for editing
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $available_quantity = $row['available_quantity'];
    } else {
        echo "Product not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Add your CSS styles here -->
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo $description; ?></textarea><br><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>"><br><br>
        <label for="available_quantity">Available Quantity:</label><br>
        <input type="text" id="available_quantity" name="available_quantity" value="<?php echo $available_quantity; ?>"><br><br>
        <input type="submit" name="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>
