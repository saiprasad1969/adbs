<?php
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

$responseMessage = ""; // Initialize response message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use the @ symbol to suppress undefined variable warnings
    $foodid = @mysqli_real_escape_string($conn, $_POST['foodid']);
    $foodName = @mysqli_real_escape_string($conn, $_POST['foodName']);
    $price = @mysqli_real_escape_string($conn, $_POST['price']);
    $description = @mysqli_real_escape_string($conn, $_POST['description']);
    $category = @mysqli_real_escape_string($conn, $_POST['category']);
    $foodType = @mysqli_real_escape_string($conn, $_POST['foodType']);

    // Handle image upload
    $targetDir = "img/";

    // Ensure the "img" directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Use the @ symbol to suppress undefined variable warnings
    $imageFile = @$_FILES["imageFile"];
    $imageFileName = @pathinfo($imageFile["name"], PATHINFO_FILENAME);
    $imageFileType = @pathinfo($imageFile["name"], PATHINFO_EXTENSION);

    // Use the @ symbol to suppress undefined variable warnings
    $targetFile = @$targetDir . $imageFileName . '.' . $imageFileType;

    // Now, $targetFile should have the correct path and filename

    // Check if the file was uploaded successfully before moving it
    if (move_uploaded_file(@$_FILES["imageFile"]["tmp_name"], $targetFile)) {
        $imageURL = $targetFile;

        // Insert data into the database using prepared statement
        $sql = "INSERT INTO foods (foodid, foodName, price, description, category, foodType, imageURL) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssdssss", $foodid, $foodName, $price, $description, $category, $foodType, $imageURL);

        if ($stmt->execute()) {
            $responseMessage = "Food added successfully!";
        } else {
            $responseMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } 
}

// Close the database connection
$conn->close();

// Send the response message to the client
echo $responseMessage;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }

        #sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            padding-top: 40px;
			margin-top:20px;
        }

        #content {
            margin-left: 260px;
            padding: 20px;
        }

        #food-form, #orders-list {
            display: none;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 12px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
            cursor: pointer;
            background-color: #333;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        li:hover {
            background-color: orange;
        }

        form {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: blue;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #333;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: orange;
        }

        h2 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #333;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            cursor: pointer;
            color: white;
        }

        li:hover {
            background-color: orange;
        }
    </style>
</head>
<body>

    <header>
        <h1>Admin Panel</h1>
    </header>

    <div id="sidebar">
        <ul>
            <li onclick="showFoodForm()">Add Food</li>
            <li onclick="showUpdateForm()">Update Food</li>
            <li onclick="showOrders()">View Orders</li>
            <li onclick="showOrder()">Home</li>
            <li onclick="showregister()">Registered customer</li>
        </ul>
    </div>

    <div id="content">
        <div id="food-form">
            <h2>Add Food</h2>
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!-- Add this inside your form -->
                <?php
// Function to generate and return the next food ID
function generateProductId() {
    $conn = mysqli_connect("localhost", "root", "", "food_order");

    if (!$conn) {
        return 1001; // Default starting ID if unable to connect to the database
    }

    $sql = "SELECT MAX(foodid) as max_id FROM foods";
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $maxId = $row['max_id'] ?? 1000; // Use 1000 if max_id is null (no records in the table)
        return $maxId >= 1001 ? $maxId + 1 : 1001;
    }

    return 1001; // Default starting ID if unable to retrieve from the database
}

?>

<label for="foodid">Food ID:</label>
<input type="text" id="foodid" name="foodid" value="<?= generateProductId() ?>" readonly required>





                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div style="flex-basis: 48%;">
                        <label for="foodName">Food Name:</label>
                        <input type="text" id="foodName" name="foodName" required>
                    </div>
                    <div style="flex-basis: 48%;">
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" required>
                    </div>
                </div>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>

                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="dosa">Dosa</option>
                    <option value="sandwich">Sandwich</option>
                    <option value="poori">Poori</option>
                    <option value="idli">Idli</option>
                    <option value="paratha">Paratha</option>
                    <option value="biryani">Biryani</option>
                    <option value="pizza">Pizza</option>
                </select>

                <label>Food Type:</label>
                <div style="display: flex; align-items: center;">
                    <div style="margin-right: 10px;">
                        <input type="radio" id="veg" name="foodType" value="veg" required>
                        <label for="veg">Veg</label>
                    </div>
                    <div>
                        <input type="radio" id="nonVeg" name="foodType" value="nonVeg" required>
                        <label for="nonVeg">Non-Veg</label>
                    </div>
                </div>

                <label for="imageFile">Select Image:</label>
                <input type="file" id="imageFile" name="imageFile" accept="image/*" required>

                <button type="submit">Add Food</button>
            </form>
        </div>

        <div id="orders-list">
            <h2>Orders</h2>
            <ul>
                <li>Order 1</li>
                <li>Order 2</li>
                <!-- Display orders dynamically -->
            </ul>
        </div>
    </div>
    <div id="update-food-form" style="display: none;">
        <h2>Update Food</h2>
        <form method="post" action="update_food.php">
            <label for="foodIdToUpdate">Food ID to Update:</label>
            <input type="text" id="foodIdToUpdate" name="foodIdToUpdate" required>

            <label for="updatedFoodName">Updated Food Name:</label>
            <input type="text" id="updatedFoodName" name="updatedFoodName" required>

            <!-- Add other fields for updating -->

            <button type="submit">Update Food</button>
        </form>
    </div>
    <script>
        function showFoodForm() {
            document.getElementById('food-form').style.display = 'block';
            document.getElementById('orders-list').style.display = 'none';
        }

        function showOrders() {
        // Redirect to adminorder.php
        window.location.href = 'adminorder.php';
    }
    function showOrder() {
        // Redirect to adminorder.php
        window.location.href = 'dum.php';
    }
    function showregister() {
        // Redirect to adminorder.php
        window.location.href = 'regis.php';
    }
        function showUpdateForm() {
            // Redirect to update.php
            window.location.href = 'update.php';
        }
    </script>

</body>
</html>
