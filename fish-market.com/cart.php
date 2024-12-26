<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
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

// Fetch cart items
$email = $_SESSION['email'];
$sql = "SELECT * FROM unique_product_view WHERE email = '$email'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
       
        body {
            background-image: url('https://wallpapercave.com/wp/ffu4sLX.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            animation: fadeIn 2s;
        }
        
        .header, .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            animation: slideDown 1s;
        }
        .nav {
            display: flex;
            justify-content: center;
            background-color: #444;
            flex-wrap: wrap;
            animation: slideRight 1s;
        }
        .nav a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s;
            animation: bounceIn 1s;
        }
        .nav a:hover {
            background-color: #555;
            animation: pulse 1s;
        }
        .container {
            padding: 20px;
            animation: fadeInUp 1.5s;
        }
        h2 {
            text-align: center;
            color: #333;
            animation: fadeInLeft 1.5s;
        }
        .card {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            animation: fadeInRight 1.5s;
        }
        .card-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 calc(33% - 40px);
            box-sizing: border-box;
            animation: zoomIn 1.5s;
        }
        .card-item img {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            animation: rotateIn 1s;
        }
        .card-item h3 {
            margin-top: 0;
            animation: fadeInDown 1.5s;
        }
        .card-item p {
            margin: 5px 0;
            animation: fadeIn 1.5s;
        }
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            animation: fadeInUpBig 1.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        @keyframes slideRight {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes bounceIn {
            from, 20%, 40%, 60%, 80%, to {
                -webkit-animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
                animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
            }
            0% {
                opacity: 0;
                -webkit-transform: scale3d(.3, .3, .3);
                transform: scale3d(.3, .3, .3);
            }
            20% {
                -webkit-transform: scale3d(1.1, 1.1, 1.1);
                transform: scale3d(1.1, 1.1, 1.1);
            }
            40% {
                -webkit-transform: scale3d(.9, .9, .9);
                transform: scale3d(.9, .9, .9);
            }
            60% {
                opacity: 1;
                -webkit-transform: scale3d(1.03, 1.03, 1.03);
                transform: scale3d(1.03, 1.03, 1.03);
            }
            80% {
                -webkit-transform: scale3d(.97, .97, .97);
                transform: scale3d(.97, .97, .97);
            }
            to {
                opacity: 1;
                -webkit-transform: scale3d(1, 1, 1);
                transform: scale3d(1, 1, 1);
            }
        }
        @keyframes pulse {
            from {
                transform: scale3d(1, 1, 1);
            }
            50% {
                transform: scale3d(1.05, 1.05, 1.05);
            }
            to {
                transform: scale3d(1, 1, 1);
            }
        }
        @keyframes fadeInUp {
            from {
                transform: translate3d(0, 100%, 0);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }
        @keyframes fadeInLeft {
            from {
                transform: translate3d(-100%, 0, 0);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }
        @keyframes fadeInRight {
            from {
                transform: translate3d(100%, 0, 0);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale3d(0.3, 0.3, 0.3);
            }
            50% {
                opacity: 1;
            }
        }
        @keyframes rotateIn {
            from {
                transform: rotate3d(0, 0, 1, -200deg);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }
        @keyframes fadeInDown {
            from {
                transform: translate3d(0, -100%, 0);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }
        @keyframes fadeInUpBig {
            from {
                transform: translate3d(0, 2000px, 0);
                opacity: 0;
            }
            to {
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }

        .nav a.active {
            background-color: #007bff;
        }

        /* Separate animation for highlighting active link */
        .nav a.active {
            animation: fadeInAndScale 0.5s ease-in-out;
        }

        .highlight {
    background-color: #34C759; /* Green color */
    color: #FFFFFF; /* White text color */
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.highlight:hover {
    background-color: #007bff; /* Blue color */
}

.highlight:active {
    background-color: #34C759; /* Green color */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.delivery-notice {
    background-color: #f8d7da; /* Light red background color */
    color: #721c24; /* Dark red text color */
    border: 1px solid #f5c6cb; /* Red border */
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
}

.delivery-notice p {
    margin: 0;
    font-size: 16px;
    text-align: center;
}
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Cart</h1>
    </div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php" class="active">Cart</a>
        <a href="orders.php">Orders</a>
        <a href="profile.php">Profile</a>
        <a href="contact.html">Contact</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Cart Items</h2>
       
    
        <form name="orderForm" method="post" action="complete_order.php">
            <div class="card">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='card-item'>";
                        echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'>";
                        echo "<h3>" . $row['name'] . "</h3>";
                        echo "<p>" . $row['description'] . "</p>";
                        echo "<p>Price: <span id='price_" . $row['product_id'] . "'>$" . $row['price'] . "</span></p>";
                        echo "<p>Available Quantity: " . $row['available_quantity'] . "</p>";
                        if ($row['available_quantity'] == 0) {
                            echo "<p>Out of Stock, Available Soon</p>";
                        } else if ($row['available_quantity'] > 10) {
                            echo "<p>Quantity (KG): <input id='quantity_" . $row['product_id'] . "' name='quantity_" . $row['product_id'] . "' data-product-id='" . $row['product_id'] . "' type='number' value='1' min='1' max='10' onchange='updateQuantity(" . $row['product_id'] . ", this.value)'></p>";
                        } else if ($row['available_quantity'] >= 1 && $row['available_quantity'] <= 10) {
                            echo "<p>Quantity (KG): <input id='quantity_" . $row['product_id'] . "' name='quantity_" . $row['product_id'] . "' data-product-id='" . $row['product_id'] . "' type='number' value='1' min='1' max='" . $row['available_quantity'] . "' onchange='updateQuantity(" . $row['product_id'] . ", this.value)'></p>";
                        }
                        echo "<p>Amount: <span id='amount_" . $row['product_id'] . "'>$" . $row['price'] . "</span></p>";
                        echo "<button type='button' onclick='removeFromCart(" . $row['product_id'] . ")'>Remove</button>";
                        echo "</div>";
                        echo "<input type='hidden' name='product_id[]' value='" . $row['product_id'] . "'>";
                        echo "<input type='hidden' name='quantity[]' id='hidden_quantity_" . $row['product_id'] . "' value='1'>";
                    }
                } else {
                    echo "<p>No items in cart</p>";
                }
                ?>
            </div>
            <h2>Delivery Details</h2>
           
        <table>
            <tr>
                <th>Full Name</th>
                <td><input type="text" name="full_name" required></td>
            </tr>
            <tr>
                <th>Full Address</th>
                <td><textarea name="address" required ></textarea></td>
            </tr>
            <tr>
                <th>Delivery Address Format</th>
                <td><p>Format of address: state, district, village, street, zip code</p></td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td><input type="text" name="mobile_number" required></td>
            </tr>
        </table>
        <div class="center">
            <input type="submit" value="Complete Order" class="highlight">
        </div>
    </form>
        <div class="delivery-notice">
        <p><strong>Orders are delivered within 2 days after a successful order. Cash on delivery is the only available payment method.</strong></p>
    </div>
        <div id="total_amount" class="center"></div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Fish Store. All rights reserved.</p>
    </div>
    <script>
    // Calculate total on page load
    window.addEventListener('load', function() {
        calculateTotal();
    });

    function updateQuantity(productId, quantity) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var amount = parseFloat(this.responseText);
                document.getElementById("amount_" + productId).innerHTML = "$" + amount.toFixed(2);
                document.getElementById("hidden_quantity_" + productId).value = quantity;
                calculateTotal();
                animatePriceUpdate(productId);
            }
        };
        xhttp.open("POST", "update_quantity.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("product_id=" + productId + "&quantity=" + quantity);
    }

    function calculateTotal() {
        var total = 0;
        var quantities = document.querySelectorAll("[id^='quantity_']");
        quantities.forEach(function(item) {
            var quantity = parseFloat(item.value);
            if (!isNaN(quantity)) {
                var productId = item.getAttribute('data-product-id');
                var price = parseFloat(document.getElementById("price_" + productId).innerHTML.replace("$", ""));
                var amount = price * quantity;
                document.getElementById("amount_" + productId).innerHTML = "$" + amount.toFixed(2);
                total += amount;
            }
        });
        if (!isNaN(total)) {
            document.getElementById("total_amount").innerHTML = "Total: $" + total.toFixed(2);
        } else {
            document.getElementById("total_amount").innerHTML = "Total: $0.00";
        }
        animateTotalAmount();
    }

    function removeFromCart(productId) {
        var confirmation = confirm("Are you sure you want to remove this product from the cart?");
        if (confirmation) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    location.reload(); // Reload the page to update the cart items
                }
            };
            xhttp.open("POST", "remove_from_cart.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("remove_product_id=" + productId);
        }
    }

    function animatePriceUpdate(productId) {
        var amountElement = document.getElementById("amount_" + productId);
        amountElement.classList.add('animate');
        setTimeout(function() {
            amountElement.classList.remove('animate');
        }, 1000);
    }

    function animateTotalAmount() {
        var totalAmountElement = document.getElementById("total_amount");
        totalAmountElement.classList.add('animate');
        setTimeout(function() {
            totalAmountElement.classList.remove('animate');
        }, 1000);
    }

    // Add animation class to animate total amount and price updates
    const style = document.createElement('style');
    style.innerHTML = `
        .animate {
            animation: flash 1s;
        }
        @keyframes flash {
            0% {
                background-color: yellow;
            }
            100% {
                background-color: transparent;
            }
        }
    `;
    document.head.appendChild(style);

   
        
  
    </script>
</body>
</html>
