<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registers Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<?php
// Database connection details
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

// Query to retrieve data from the "register" table
$sql = "SELECT * FROM register";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data as a table
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th>Address</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phoneno"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>0 results</p>";
}

// Close the database connection
$conn->close();
?>

</body>
</html>
