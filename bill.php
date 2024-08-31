<?php
session_start();

// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['email']; // Replace with the actual session variable name

// Retrieve user information from the "register" table
$userQuery = "SELECT * FROM register WHERE email = '$userId'";
$userResult = $conn->query($userQuery);

if ($userResult === false) {
    die("Error executing user query: " . $conn->error);
}

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Retrieve order information from the "orders" table
$orderQuery = "SELECT * FROM orders WHERE user_id = '$userId'";
$orderResult = $conn->query($orderQuery);

if ($orderResult === false) {
    die("Error executing order query: " . $conn->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Bill</title>
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
    </style>
</head>
<body>

<h2>Order Bill</h2>

<!-- Display user information -->
<table border="1">
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Email</th>
        <!-- Add more columns as needed -->
    </tr>
    <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['username']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <!-- Add more cells as needed -->
    </tr>
</table>

<!-- Display order information -->
<?php if ($orderResult->num_rows > 0): ?>
    <h3>Order Details</h3>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Food ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Order Date</th>
            <!-- Add more columns as needed -->
        </tr>
        <?php while ($order = $orderResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['foodid']; ?></td>
                <td><?php echo $order['product_name']; ?></td>
                <td><?php echo $order['price']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <!-- Add more cells as needed -->
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No orders found for this user.</p>
<?php endif; ?>

</body>
</html>
