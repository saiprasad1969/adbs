<?php
session_start();

// Sample database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // You should perform proper password hashing and validation in a real-world scenario
    $hashedPassword = md5($password);

    $sql = "SELECT * FROM adminlogin WHERE username = '$username' AND password = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Admin login successful
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php"); 
        exit();
    } 
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 500px;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width:150px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form action="admin.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
