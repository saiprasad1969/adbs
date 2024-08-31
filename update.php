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

// Handle form submission to update or delete a food item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a delete request
    if (isset($_POST['deleteFoodId'])) {
        $foodidToDelete = mysqli_real_escape_string($conn, $_POST['deleteFoodId']);

        $deleteSql = "DELETE FROM foods WHERE foodid = '$foodidToDelete'";

        if ($conn->query($deleteSql) === TRUE) {
            echo "Record deleted successfully!";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        // It's an update request
        $foodid = mysqli_real_escape_string($conn, $_POST['foodid']);
        $foodName = mysqli_real_escape_string($conn, $_POST['foodName']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $foodType = mysqli_real_escape_string($conn, $_POST['foodType']);

        // Handle file upload
        if ($_FILES['foodImage']['error'] == 0) {
            $targetDir = "uploads/"; // Specify your target directory
            $targetFile = $targetDir . basename($_FILES['foodImage']['name']);

            if (move_uploaded_file($_FILES['foodImage']['tmp_name'], $targetFile)) {
                // File uploaded successfully, update the imageURL in your database
                $imageURL = $targetFile;
                
                // Include $imageURL in your database update query
                $sql = "UPDATE foods SET
                    foodName = '$foodName',
                    price = '$price',
                    description = '$description',
                    category = '$category',
                    foodType = '$foodType',
                    imageURL = '$imageURL'
                    WHERE foodid = '$foodid'";

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully!";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            // File upload error handling if needed
            echo "File upload error: " . $_FILES['foodImage']['error'];
        }
    }
}

// Fetch data from the "foods" table
$sql = "SELECT * FROM foods";
$result = $conn->query($sql);
?>

<!-- Rest of your HTML code -->

<?php
// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Food</title>
    <style>
         body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .edit-form {
        display: none;
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        width:500px;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    div.radio-group {
        margin-bottom: 16px;
    }

    div.radio-group input {
        margin-right: 8px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>

    <h2>Food Data</h2>

    <!-- Table container -->
   <!-- Table container -->
<table>
    <tr>
        <th>ID</th>
        <th>Food Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Category</th>
        <th>Food Type</th>
        <th>Image URL</th>
        <th>Edit</th>
    </tr>

    <?php
    // Loop through the data and generate table rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-foodid='" . $row['foodid'] . "'>"; // Add data-foodid attribute
        echo "<td>" . $row['foodid'] . "</td>";
        echo "<td>" . $row['foodName'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "<td>" . $row['foodType'] . "</td>";
    
        // Display the image directly in the table cell
        echo "<td>";
        echo "<img src='" . $row['imageURL'] . "' alt='Food Image' style='max-width: 100px; max-height: 100px;'>";
        echo "</td>";
    
        // Add an "Edit" button with an onclick event
        echo "<td>";
        echo "<button onclick='editFoodForm(" . $row['foodid'] . ")'>Edit</button>";
        echo "<button onclick='deleteFood(" . $row['foodid'] . ")' style='background-color: red;'>Delete</button>";
        echo "</td>";
    
        echo "</tr>";
    }
?>    
</table>

<div id="overlay" class="overlay" onclick="closeEditForm()"></div>
    <!-- Edit form initially hidden -->
    <div id="edit-form-container" class="edit-form">
    <h2>Edit Food</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <label for="foodid">Food ID:</label>
        <input type="text" id="foodid" name="foodid" readonly required>

        <label for="foodName">Food Name:</label>
        <input type="text" id="foodName" name="foodName" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required>

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
        <div>
            <input type="radio" id="veg" name="foodType" value="veg" required>
            <label for="veg">Veg</label>
        </div>
        <div>
            <input type="radio" id="nonVeg" name="foodType" value="nonVeg" required>
            <label for="nonVeg">Non-Veg</label>
        </div>

        <!-- Input field for image -->
        <label for="foodImage">Food Image:</label>
        <input type="file" id="foodImage" name="foodImage">

        <button type="submit">Update Food</button>
        <button type="button" onclick="closeEditForm()">Close</button>
    </form>
</div>


    <script>
    // Function to handle the edit button click and show the edit form
    function editFoodForm(foodId) {
        // Find the row corresponding to the clicked "Edit" button
        var row = document.querySelector("tr[data-foodid='" + foodId + "']");

        // Fetch the data from the row
        var foodName = row.querySelector("td:nth-child(2)").innerText;
        var price = row.querySelector("td:nth-child(3)").innerText;
        var description = row.querySelector("td:nth-child(4)").innerText;
        var category = row.querySelector("td:nth-child(5)").innerText;
        var foodType = row.querySelector("td:nth-child(6)").innerText;

        // Populate the form
        document.getElementById('foodid').value = foodId;
        document.getElementById('foodName').value = foodName;
        document.getElementById('price').value = price;
        document.getElementById('description').value = description;
        document.getElementById('category').value = category;

        if (foodType === 'veg') {
            document.getElementById('veg').checked = true;
        } else {
            document.getElementById('nonVeg').checked = true;
        }

        // Display the overlay and edit form
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('edit-form-container').style.display = 'block';
    }

    // Function to close the edit form
    function closeEditForm() {
        // Hide the overlay and edit form
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('edit-form-container').style.display = 'none';
    }
    function deleteFood(foodId) {
    // Add your logic to confirm the deletion if needed
    var confirmDelete = confirm("Are you sure you want to delete this food item?");
    
    if (confirmDelete) {
        // Perform the deletion or submit a form to handle it
        // For example, you can use AJAX to send a request to the server for deletion
        // Here, I'm assuming you have a form with an action to handle deletions
        var form = document.createElement("form");
        form.method = "post";
        form.action = "<?php echo $_SERVER['PHP_SELF']; ?>";

        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "deleteFoodId";
        input.value = foodId;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    }
}

</script>


</body>
</html>
