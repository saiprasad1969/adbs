<?php
// Start the session
session_start();

// Connect to your MySQL database (replace these values with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Check if the username already exists
    $check_stmt = $conn->prepare("SELECT * FROM register WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Username already exists, store the error message in a session variable
        $_SESSION['error_message'] = "Username already exists";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO register (username, email, password, phoneno) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $phone);

        if ($stmt->execute()) {
            // Registration successful, redirect to the login page
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: register.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement and database connection
    $check_stmt->close();
    $conn->close();
}

// Display the error message if it exists and then unset it
?>
<div class="messages-container">
    <?php
    // Display the error message if it exists and then unset it
    if (isset($_SESSION['error_message'])) {
        echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }

    // Display the success message if it exists and then unset it
    if (isset($_SESSION['success_message'])) {
        echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
        unset($_SESSION['success_message']);
    }
    ?>
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            background-image: url('img/bg4.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin-right:500px;
        }

        form {
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .success-message {
            color: black;
            
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 16px;
            font-size: 14px;
        }

        a {
            display: block;
            margin-top: 16px;
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        a:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            font-weight: bold;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="card">
        <form action="register.php" method="post">
            <h2>Registration</h2>
          <b> <label for="username">Username:</label>
            <input type="text" id="username" name="username" pattern="[a-zA-Z]+" title="Username must be alphanumeric and not empty" required>


            <label for="email">Email:</label>
            <input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" title="Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one digit." required>

            <label for="phone">Phone no:</label>
            <input type="text" id="phone" name="phone" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" required>

            <button type="submit" name="submit">Register</button>

            </b>

            
            
            <p>Already have an account? <a href="logins.php">Login here</a></p>
        </form>
    </div>
    <div class="messages-container">
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }

            if (isset($_SESSION['success_message'])) {
                echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            ?>
        </div>
</body>
</html>

