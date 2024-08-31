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

// Fetch user details and their orders from the database
$userDetailsQuery = "SELECT users.*, foods.*, orders.quantity, orders.price, orders.order_date 
                    FROM users 
                    LEFT JOIN orders ON users.id = orders.user_id
                    LEFT JOIN foods ON orders.food_id = foods.id";
$userDetailsResult = $conn->query($userDetailsQuery);

// Check if there are any results
if ($userDetailsResult->num_rows > 0) {
    echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>User Details</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        padding: 10px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <h1>User Details with Purchased Products</h1>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Food ID</th>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>';

    while ($row = $userDetailsResult->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['user_id'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['food_id'] . '</td>
                <td>' . $row['food_name'] . '</td>
                <td>' . $row['quantity'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['order_date'] . '</td>
            </tr>';
    }

    echo '</tbody></table></body></html>';
} else {
    echo "No user details found.";
}

// Close the database connection
$conn->close();
?>
