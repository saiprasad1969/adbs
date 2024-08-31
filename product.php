<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: logins.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : null;

// Use prepared statement to prevent SQL injection
$sql = "SELECT * FROM foods WHERE category = ?";

// If minPrice and maxPrice are provided, add conditions for price range
if ($minPrice !== null && $maxPrice !== null) {
    $sql .= " AND price BETWEEN ? AND ?";
}

$stmt = $conn->prepare($sql);

// Prepare the statement with dynamic types based on parameter presence
if ($minPrice !== null && $maxPrice !== null) {
    $stmt->bind_param("sdd", $category, $minPrice, $maxPrice);
} else {
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();

// Function to calculate the total price for a product
function calculateTotalPrice($price, $quantity) {
    return $price * $quantity;
}

// Function to remove an item from the cart
function removeFromCart($foodId) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $foodId) {
            unset($_SESSION['cart'][$key]);
            return true; // Indicate successful removal
        }
    }
    return false; // Indicate that the item was not found
}

// Function to insert order into the database
function insertOrder($userId, $foodId, $productName, $price, $quantity) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "food_order";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $insertOrderQuery = "INSERT INTO orders (id, foodid, product_name, price, quantity) 
                    VALUES ('$userId', '$foodId', '$productName', '$price', '$quantity')";

    if ($conn->query($insertOrderQuery) === TRUE) {
        echo "Order inserted successfully";
    } else {
        echo "Error inserting order: " . $conn->error;
    }

    $conn->close();
}

// Handle actions related to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'finalize') {
    // Replace 'id' with the actual user ID key in your $_SESSION
    $userId = $_SESSION['id'];

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            insertOrder($userId, $item['id'], $item['name'], $item['price'], $item['quantity']);
        }

        // Clear the cart after inserting into the database
    } else {
        // Handle the case where there are no items in the cart to finalize
        echo "No items in the cart to finalize";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Food List</title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f7f7;
}

/* Header Styles */
header {
    background-color: orangered;
    color: white;
    padding: 20px;
    text-align: center;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 16px;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    border-radius: 5px;
    width:120px;
    height:40px;
    transition: background-color 0.3s ease;
}

header button:hover {
    background-color: #45a049;
}

header .cart-count {
    margin-left: 10px;
    font-size: 16px;
    font-weight: bold;
}

/* Card Styles */
.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.card {
    border: 1px solid #ddd;
    padding: 15px;
    margin: 10px;
    text-align: center;
    max-width: 300px;
    flex: 1;
    box-sizing: border-box;
    background-color: #fff;
    transition: transform 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
}

.card img {
    max-width: 100%;
    height: 180px;;
    border-radius: 5px;
}

.card h3 {
    margin-top: 10px;
    color: #333;
}

.card p {
    color: #009688;
    font-weight: bold;
    margin: 10px 0;
}

.card b {
    display: block;
    margin-top: 10px;
    color: #666;
}

.card button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 16px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.card button:hover {
    background-color: #45a049;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .card {
        max-width: 100%;
    }
}

    </style>
</head>

<body>

    <!-- Header Section -->
    <header>
        <!-- You can replace '#' with the actual link to your cart page -->
        <button onclick="location.href='dum.php'">Home</button>
        <button onclick="location.href='cart.php'">Cart <span id="cartCount">(0)</span></button>
        
        <!-- Add any other header elements or navigation links as needed -->

        <!-- Search button placeholder (you can add functionality later) -->
        
    </header>
    <div id="confirmation-message">
    <!-- Card Container -->
    <div class="card-container">
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="' . $row["imageURL"] . '" alt="' . $row["foodName"] . '">';
            echo '<h3>' . $row["foodName"] . '</h3>';
            echo '<p>Rs' . $row["price"] . '</p>';
            echo '<b>' . $row["description"] . '</b>';
            echo '<div>';
            echo '<button onclick="addToCart(' . $row["foodid"] . ', \'' . $row["foodName"] . '\', ' . $row["price"] . ')">Add to Cart</button>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "Not Available";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>
</div>
</body>

</html>



 <!-- Your existing PHP code here -->

 <script>
  // Maintain a list of added items
// Maintain a list of added items
var addedItems = [];

// Function to update the cart count
function updateCartCount() {
    var cartCountElement = document.getElementById('cartCount');

    // Count the number of items in the addedItems array
    var cartCount = addedItems.length;

    // Update the cart count in the UI
    cartCountElement.textContent = '(' + cartCount + ')';
}

// Ensure that the cart count is updated when the page loads
window.onload = function () {
    updateCartCount();
};
// Function to calculate the total price for a product
function calculateTotalPrice($price, $quantity) {
    // Ensure $price and $quantity are numeric
    if (is_numeric($price) && is_numeric($quantity)) {
        return $price * $quantity;
    } else {
        // Handle the case where $price or $quantity is not numeric
        return 0; // or any appropriate default value
    }
}


// Function to add an item to the cart
// Function to add an item to the cart
function addToCart(foodId, foodName, price) {
    // Check if the item is already in the cart
    if (addedItems.includes(foodId)) {
        window.alert('Item is already in the cart');
        return;
    }

    // Fixed quantity
    var quantity = 1;

    // Perform the AJAX request to add the item to the cart
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                var response = this.responseText;
                console.log(response);

                // Check for the "add to cart" message
                if (response.includes('Item added to cart successfully')) {
                    // Add the item to the list of added items
                    addedItems.push(foodId);

                    // Display a confirmation message using window.alert
                    window.alert('Item added to cart successfully');

                    // Update the cart count after adding an item
                    updateCartCount();
                } else {
                    // Handle the case where the item was not added successfully
                    window.alert('Failed to add item to cart');
                }
            }
        }
    };

    // Send the item details and fixed quantity to cart.php
    xhttp.open("POST", "cart.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=add&foodid=" + foodId + "&foodname=" + encodeURIComponent(foodName) + "&price=" + price + "&quantity=" + quantity);
}


</script>
