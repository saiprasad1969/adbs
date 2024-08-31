<?php
session_start();

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

    // Use prepared statement to prevent SQL injection
    $insertOrderQuery = "INSERT INTO orders (id, foodid, product_name, price, quantity, date) 
                    VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($insertOrderQuery);

    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        return;
    }

    $stmt->bind_param("iisdi", $userId, $foodId, $productName, $price, $quantity);

    if ($stmt->execute() === TRUE) {
        echo "Order inserted successfully";
    } else {
        echo "Error inserting order: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Handle actions related to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'finalize') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "food_order";

    // Create a new mysqli connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $email = $_SESSION['email'];  // Assuming you store the email in the session

    // Prepare and execute the SELECT statement
    $sql = "SELECT id FROM login WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                insertOrder($userId, $item['id'], $item['name'], $item['price'], $item['quantity']);
            }

            // Clear the cart after inserting into the database
            unset($_SESSION['cart']);
        } else {
            // Handle the case where there are no items in the cart to finalize
            echo "No items in the cart to finalize";
        }
    } else {
        // Handle the case where no user is found with the specified email
        echo "No user found with the specified email";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

// Handle actions related to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    // Add to cart logic
    $foodId = $_POST['foodid'];
    $foodName = $_POST['foodname'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $_SESSION['cart'][] = ['id' => $foodId, 'name' => $foodName, 'price' => $price, 'quantity' => $quantity];

    echo 'Item added to cart successfully';
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'remove') {
    // Remove from cart logic
    $foodId = $_GET['foodid'];

    if (removeFromCart($foodId)) {
        echo 'Item removed from cart successfully';
    } else {
        echo 'Failed to remove item from cart';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue'])) {
    // Continue button clicked, insert cart data into the database
    if (!empty($_SESSION['cart'])) {
        $userId = 1; // Replace with the actual user ID, you may get this from the session or login information

        foreach ($_SESSION['cart'] as $item) {
            insertOrder($userId, $item['id'], $item['name'], $item['price'], $item['quantity']);
        }

        // Clear the cart after inserting into the database
        $_SESSION['cart'] = array();

        // Redirect to the next page
        header("Location: addpay.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize'])) {
    // Finalize button clicked, finalize the order
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Prepare and execute the SELECT statement
        $sql = "SELECT id FROM login WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    insertOrder($userId, $item['id'], $item['name'], $item['price'], $item['quantity']);
                }

                // Clear the cart after inserting into the database
                unset($_SESSION['cart']);

                // Optionally, you can redirect to a success page or perform other actions
                header("Location: success.php");
                exit();
            } else {
                // Handle the case where there are no items in the cart to finalize
                echo "No items in the cart to finalize";
            }
        } else {
            // Handle the case where the user is not found in the database
            echo "User not found in the database";
        }
    } else {
        // Handle the case where the user is not logged in
        echo "User not logged in";
    }
}else {
    echo '';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_quantity') {
    $foodId = $_POST['foodid'];
    $newQuantity = $_POST['quantity'];

    // Find the item in the session and update the quantity
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $foodId) {
            $item['quantity'] = $newQuantity;
            echo 'Item quantity updated successfully';
            exit(); // Ensure no further processing after updating the quantity
        }
    }

    // If the item was not found, you may want to handle this case accordingly
    echo 'Failed to update item quantity';
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 8px 12px;
            background-color: red;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #9e0000;
        }

        .continue-button {
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            margin-left: 1100px;
            border-radius: 5px;
        }

        .continue-button:hover {
            background-color: orangered;
        }

        .edit-input {
            width: 60px; /* Adjust the width as needed */
            padding: 5px;
            margin-right: 5px;
        }

        .save-button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .save-button:hover {
            background-color: #45a049;
        }

        .quantity-cell {
            position: relative;
        }

        .quantity {
            display: inline-block;
            padding: 5px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-right: 5px;
        }

        .edit-button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .edit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Shopping Cart</h2>
<div class="card">

<?php
    $totalAmount = 0;
    if (!empty($_SESSION['cart'])) {
        echo '<table border="1">
                <tr>
                    <th>Id</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>';

        $counter = 1; // Initialize a counter

        foreach ($_SESSION['cart'] as $item) {
            $totalAmount += calculateTotalPrice($item['price'], $item['quantity']);

            // Increment the counter and format it with leading zeros
            $formattedId = str_pad($counter++, 2, '0', STR_PAD_LEFT);

            echo '<tr>
        <td>' . $formattedId . '</td>
        <td>' . $item['name'] . '</td>
        <td id="price_' . $item['id'] . '">Rs.' . $item['price'] . '</td>
        <td class="quantity-cell">
        <input type="number" id="editQuantity_' . $item['id'] . '" class="edit-quantity-input" value="' . $item['quantity'] . '" oninput="updateTotalPrice(' . $item['id'] . ', this.value)">
        </td>
        <td id="totalPrice_' . $item['id'] . '">Rs.' . calculateTotalPrice($item['price'], $item['quantity']) . '</td>
        <td><button onclick="removeFromCart(' . $item['id'] . ')">Remove</button></td>
    </tr>';
        }

        echo '</table>';

        // Display the total amount
        echo '<p id="grandTotal">Grand Total Amount: Rs.' . number_format($totalAmount, 2) . '</p>';

        echo '<button class="continue-button" onclick="continueShopping()">Continue</button>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
    
    <form id="finalizeForm" action="" method="post">
        <button type="submit" class="finalize-button" name="action" value="finalize">Finalize</button>
    </form><br>
    <button onclick="redirectToAddPay()">Continue</button>
    <button onclick="home()">Home</button>
</div>

<script>
    function redirectToAddPay() {
        window.location.href = 'addpay.php';
    }
function updateGrandTotal() {
    var grandTotal = 0;

    // Iterate through each row in the table
    var rows = document.querySelectorAll('table tr');
    for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var quantityInput = row.querySelector('.edit-quantity-input');
        var totalAmountElement = row.querySelector('[id^="totalPrice_"]'); // Use attribute selector to match elements with id starting with "totalPrice_"

        if (quantityInput && totalAmountElement) {
            var foodId = quantityInput.id.replace('editQuantity_', ''); // Extract the foodId from the input id
            var quantity = parseFloat(quantityInput.value) || 0; // Use 0 if value is not a valid number
            var totalAmount = parseFloat(totalAmountElement.textContent.replace('Rs.', '').trim()) || 0;

            // Add the total amount for the current item to the grand total
            grandTotal += totalAmount;
        }
    }

    // Update the grand total in the HTML
    var grandTotalElement = document.getElementById('grandTotal');
    if (grandTotalElement) {
        grandTotalElement.textContent = 'Grand Total Amount: Rs.' + grandTotal.toFixed(2);
    }
}


// Ensure that the grand total is updated when the page loads
window.onload = function () {
    updateGrandTotal();
};


    // Modify the existing function to call updateGrandTotal after updating individual item totals
    function updateTotalPrice(foodId, newQuantity) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                var response = this.responseText;
                console.log(response);

                if (response.includes('Item quantity updated successfully')) {
                    // Optionally, you can display a message or perform other actions
                    alert('Item quantity updated successfully');

                    // Update the total displayed on the page
                    var totalAmountElement = document.getElementById('totalPrice_' + foodId);
                    if (totalAmountElement) {
                        var priceElement = document.getElementById('price_' + foodId);
                        var price = parseFloat(priceElement.textContent.replace('Rs.', '').trim()) || 0;
                        var newTotal = price * newQuantity;
                        totalAmountElement.textContent = 'Rs.' + newTotal.toFixed(2);
                    }
                    
                    // Update the grand total
                    updateGrandTotal();
                } else {
                    alert('Failed to update item quantity');
                }
            }
        }
    };

    // Send the updated quantity to cart.php
    xhttp.open("POST", "cart.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=update_quantity&foodid=" + foodId + "&quantity=" + newQuantity);
}


        // Send the updated quantity to cart.php
        

    // Modify the existing function to call updateGrandTotal after removing an item from the cart
    function removeFromCart(foodId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    var response = this.responseText;
                    console.log(response);

                    if (response.includes('Item removed from cart successfully')) {
                        alert('Item Removed');
                        location.reload();
                        
                        // Call the function to update the grand total
                        updateGrandTotal();
                    } else {
                        alert('Failed to remove from Cart');
                    }
                }
            }
        };

        xhttp.open("GET", "cart.php?action=remove&foodid=" + foodId, true);
        xhttp.send();
    }

    function continueShopping() {
        window.location.href = 'addpay.php';
    }
    function home() {
        window.location.href = 'dum.php';
    }
    function updateCartInLocalStorage(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Function to get the cart from localStorage
    function getCartFromLocalStorage() {
        var cart = localStorage.getItem('cart');
        return cart ? JSON.parse(cart) : [];
    }
    window.onload = function () {
        updateGrandTotal();
        // Load the cart from localStorage and set the quantities to the corresponding inputs
        loadCartFromLocalStorage();
    };
    function loadCartFromLocalStorage() {
        var cart = getCartFromLocalStorage();
        for (var i = 0; i < cart.length; i++) {
            var foodId = cart[i].id;
            var quantity = cart[i].quantity;
            var input = document.getElementById('editQuantity_' + foodId);
            if (input) {
                input.value = quantity;
            }
        }
    }

</script>


</body>
</html>