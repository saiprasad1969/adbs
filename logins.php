<?php
session_start();

// Replace these values with your actual database credentials
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: logins.php");
        exit();
    }

    // Prepare SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT id, username, password FROM register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user with the given email exists
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if ($password === $user['password']) {
            // Password is correct, set session variables and redirect
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $user['username'];
            $_SESSION['message'] = "Login successful";

            // Store login information in the "login" table
            $insertLoginQuery = "INSERT INTO login (id, email, password) VALUES (?, ?, ?)";
            $stmtInsertLogin = $conn->prepare($insertLoginQuery);
            $stmtInsertLogin->bind_param("iss", $user['id'], $email, $password);
            $stmtInsertLogin->execute();
            $stmtInsertLogin->close();

            header("Location: dum.php");
            exit();
        } else {
            // Invalid password, show an error message
            $_SESSION['error_message'] = "Invalid email or password";
        }
    } else {
        // User does not exist, show an error message
        $_SESSION['error_message'] = "Invalid email or password";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background: url('img/bg5.jpg') center center fixed;
    background-size: cover;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}
        .card {
            background-color: transparent;
            box-shadow: 0 8px 85px rgba(0, 0, 5, 5.8);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            
            width: 600px;
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 15px;
            color: #888;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>MAROLI KITCHENS</h2>
        <?php
            // Display error message if it exists
            if (isset($_SESSION['error_message'])) {
                echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }
        ?>
        <form action="logins.php" method="post">
        <label for="email">Email:</label>
            <input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit">Login</button>
            
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>
