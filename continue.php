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

// Assuming you get the customer ID from the URL (adjust as needed)
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;

// Fetch orders for the specified customer ID
$sql = "SELECT * FROM orders WHERE foodid = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param("i", $customer_id);

if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error);
}

$result = $stmt->get_result();

// Display orders
if ($result->num_rows > 0) {
    echo "<h2>Order Details for Customer ID: $customer_id</h2>";
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['foodid']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['price']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No orders found for Customer ID: $customer_id";
}

$stmt->close();
$conn->close();
?>
