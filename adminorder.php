<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            width: 100%;
        }

        .card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            width: 700px;
        }
    </style>
</head>
<body>

<header>
    <h1>Order History</h1>
</header>

<?php
// Sample database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT o.*, r.username, r.email, r.address, r.phoneno 
        FROM orders o
        JOIN register r ON o.id = r.id
        ORDER BY o.date DESC"; // Order by date in descending order
// Order by date to group orders made at the same time

// Fetch and display order history with user information
$result = $conn->query($sql);

if ($result === false) {
    die("Error in SQL query: " . $conn->error);
}

$currentDateTime = null; // Variable to store the current date and time

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderDateTime = $row["date"];

        // Check if the order has the same date and time as the previous one
        if ($orderDateTime !== $currentDateTime) {
            // If not, start a new card for the current order
            if ($currentDateTime !== null) {
                echo "</div>"; // Close the previous card
            }

            echo "<div class='card'>";
            echo "<h3>Order ID: " . $row["id"] . "</h3>";
            echo "<p>Order Date: " . $orderDateTime . "</p>";
            $currentDateTime = $orderDateTime;
        }

        // Display order details for the current order
        echo "<p>Item Name: " . $row["product_name"] . "</p>";
        echo "<p>Total Amount: " . $row["price"] . "</p>";
        echo "<p>Quantity: " . $row["quantity"] . "</p>";
        echo "<p>Email: " . $row["email"] . "</p>"; // Display email
        echo "<p>Address: " . $row["address"] . "</p>"; // Display address
    }

    echo "</div>"; // Close the last card
} else {
    echo "No orders found.";
}

$conn->close();
?>

</body>
</html>
