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

// Check if the form is submitted for adding a bank account
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's the address form submission
    if (isset($_POST['address'])) {
        // Retrieve and sanitize user inputs
        $address = htmlspecialchars($_POST['address']);
        $houseNo = htmlspecialchars($_POST['house_no']);
        $city = htmlspecialchars($_POST['city']);
        $district = htmlspecialchars($_POST['district']);
        $state = htmlspecialchars($_POST['state']);
        $pincode = htmlspecialchars($_POST['pincode']);

        // Session start - make sure to start the session before using $_SESSION
        session_start();

        // Assuming you have a user email available in $_SESSION['email']
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            // Update the "register" table with the address
            $updateAddressSql = "UPDATE register SET address = ? WHERE email = ?";
            $stmt = $conn->prepare($updateAddressSql);
            $stmt->bind_param("ss", $address, $email);
            $success = $stmt->execute();
            $stmt->close();

            if ($success) {
                echo 'Address updated!';
               
                exit();
            } else {
                echo 'Failed to update address. Please try again.';
            }
        } else {
            echo "User email not found in session. Please make sure the user is logged in.";
        }

        // Session end
        session_write_close();
    }

    // Check if it's the bank account form submission
    if (isset($_POST['checkAndAddAccount'])) {
        // Get the request body
        $accNo = $_POST['accNo'];  // Updated variable name
        $email = $_POST['email'];

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM bank_details WHERE acc_no = ? AND email = ?");
        $stmt->bind_param("ss", $accNo, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Debugging: Echo statements
        echo "Received accNo: $accNo, email: $email<br>";

        if ($result && $result->num_rows > 0) {
            echo 'Bank account added!';
            session_start();
            if (isset($_SESSION['totalAmount'])) {
                $totalAmount = $_SESSION['totalAmount'];
                echo "<p>Total Amount from Cart: $totalAmount</p>";
            } else {
                echo 'Total amount not found in the cart.';
            }
            session_write_close();
        } else {
            echo 'Bank account not found. Please check your details.';
        }

        $stmt->close();
    }

    $conn->close();
    exit(); // Terminate further execution after processing the form
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Form</title>
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

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 800px;
            margin-top: 20px;
            margin-right: 700px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 650px;;
        }

        button:hover {
            background-color: #45a049;
        }
        .message {
            font-size: 18px;
            margin-left: 650px;
            color: green;
            display: none;
        }
        .card {
    margin-top: -510px;
    margin-left: 400px;
    width: 200px; /* Set the desired width */
    padding: 15px; /* Add padding to the card content */
    box-sizing: border-box; /* Include padding and border in the total width/height */
}

    </style>
</head>
<body>

<header>
    <h1>Address Form</h1>
</header>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $address = htmlspecialchars($_POST['address']);
    $houseNo = htmlspecialchars($_POST['house_no']);
    $city = htmlspecialchars($_POST['city']);
    $district = htmlspecialchars($_POST['district']);
    $state = htmlspecialchars($_POST['state']);
    $pincode = htmlspecialchars($_POST['pincode']);

    // Display the entered address data
    
} else {
    // Display the address form
    echo '
    <form method="post" action="">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" readonly>

        <label for="house_no">House No:</label>
        <input type="text" id="house_no" name="house_no" oninput="updateAddress()" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" oninput="updateAddress()" required>

        <label for="district">District:</label>
        <input type="text" id="district" name="district" oninput="updateAddress()" required>

        <label for="state">State:</label>
        <input type="text" id="state" name="state" oninput="updateAddress()" required>

        <label for="pincode">Pincode:</label>
        <input type="text" id="pincode" name="pincode" oninput="updateAddress()" required>

        <button>Add your Address</button>
    </form>

    <script>
        function updateAddress() {
            // Combine values of house_no, city, district, state, and pincode and set it to the address field
            var houseNo = document.getElementById("house_no").value;
            var city = document.getElementById("city").value;
            var district = document.getElementById("district").value;
            var state = document.getElementById("state").value;
            var pincode = document.getElementById("pincode").value;

            var fullAddress = houseNo + ", " + city + ", " + district + ", " + state + ", " + pincode;
            document.getElementById("address").value = fullAddress;
        }
    </script>';
}
?>

<div class="card">
    <form>
   
     <br><b>   <label for="paymentMethod">Payment Method:</label></b>
        <div>
            <input type="radio" id="cashOnDelivery" name="paymentMethod" value="cashOnDelivery" checked>
            <label for="cashOnDelivery">Cash on Delivery</label>
        </div>
       
           
        </div>
    </form>
    <br>
    <button type="button" style="font-size:18px; width:200px; margin-top: 20px;" onclick="showOrderPlacedMessage()">Continue Your Order</button>

<!-- Display message container near the button -->
<div class="message" id="orderMessage"></div>    <script>
        function checkAndAddAccount() {
            var accNoValue = document.getElementById('accNo').value;
            var emailValue = document.getElementById('email').value;

            var formData = new FormData();
            formData.append('checkAndAddAccount', true);
            formData.append('accNo', accNoValue);
            formData.append('email', emailValue);

            fetch('', {  // Add the correct path to validateAccount.php
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
            })
            .catch(error => {
                console.error('Error checking account:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var ePaymentRadio = document.getElementById('ePayment');
            var ePaymentDetails = document.getElementById('ePaymentDetails');

            ePaymentRadio.addEventListener('change', function () {
                ePaymentDetails.style.display = ePaymentRadio.checked ? 'block' : 'none';
            });
        });
        function updateAddress() {
        var form = document.getElementById('address-form');
        var formData = new FormData(form);

        fetch('', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('message-container').innerHTML = result;
        })
        .catch(error => {
            console.error('Error updating address:', error);
        });
    }
    function includeScript(totalAmount) {
            // Display the total amount in the amount input field
            document.getElementById('amount').value = ' Rs.' + totalAmount.toFixed(2);
        }

        // Call the function and pass the total amount
        includeScript(<?php echo isset($_SESSION['totalAmount']) ? $_SESSION['totalAmount'] : 0; ?>);
    <script>
    function updateAddress() {
        // Combine values of house_no, city, district, state, and pincode and set it to the address field
        var houseNo = document.getElementById("house_no").value;
        var city = document.getElementById("city").value;
        var district = document.getElementById("district").value;
        var state = document.getElementById("state").value;
        var pincode = document.getElementById("pincode").value;

        var fullAddress = houseNo + ", " + city + ", " + district + ", " + state + ", " + pincode;
        document.getElementById("address").value = fullAddress;
    }
</script>
<script>
function showOrderPlacedMessage() {
        var messageContainer = document.getElementById('orderMessage');
        messageContainer.textContent = 'Order placed successfully!';
        messageContainer.style.display = 'block';
    }
</script>

    </script>
</body>
</html>
