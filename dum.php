<!-- product.php -->
<?php
session_start();
if (isset($_SESSION['username'])) {
    $loggedInUserName = $_SESSION['username'];
} else {
    $loggedInUserName = 'Guest';
}
?>
  <!DOCTYPE html>
<html>
<head>
  <title>Food Delivery Website</title>
  <style>
     .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      
      background:  url('img/bg2.webp');
      background-repeat: no-repeat;
      background-size: cover;
    }

    header {
      background-color: #ff6347;
      color: #fff;
      padding: 10px 0;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 36px;
    }

    header p {
      margin: 10px 0;
      font-size: 18px;
    }

    nav {
      background-color: #333;
      text-align: center;
    }

    nav a {
      display: inline-block;
      padding: 15px 20px;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
      margin: 0 10px;
    }

    nav a:hover {
      background-color: #555;
    }

    /* Advertisement banner styles */
    .advertisement {
      text-align: center;
      padding: 10px;
      display: flex;
      justify-content: center;
      position: relative;
    }

    /* Styles for the images */
    .advertisement img {
      width: 500px; /* Adjust the width as needed */
      height: 200px; /* Adjust the height as needed */
      margin: 10px 0; /* Add margin for spacing between images */
    }

    .slider {
      display: flex;
      position: absolute;
      left: 0; /* Start the slider from the left end */
    }

    .slider a {
      flex: 0 0 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .left-arrow, .right-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    .left-arrow {
      left: 20px;
    }

    .right-arrow {
      right: 20px;
    }

    h2 {
      margin-left: 30px;
      margin-top: 40px;
    }

    /* Additional image styles */
    .additional-images {
      display: flex;
      justify-content: flex-start; /* Align images to the left */
      align-items: center;
      margin-top: 20px;
      margin-left: 30px;
    }

    .additional-images a {
      text-decoration: none; /* Remove underlines from anchor tags */
    }

    .image-container {
      text-align: center;
    }

    .additional-images img {
      width: 200px; /* Increase the width for larger images */
      height: 150px;
      margin: 0 20px; /* Add margin for spacing between images */
    }

    .image-text {
      font-size: 16px;
      margin-top: 10px;
    }
	.menu-card {
      border: 1px solid #ddd;
      border-radius: 30px;
      padding: 20px;
      margin: 10px;
      width: 1600px;
	  height:280px;
      box-shadow: 0 0 9px rgba(0, 0, 0, 1.2);
      background-color: #f7f7f7;
    }

    .menu-title {
      font-size: 24px;
      text-align: center;
      margin-bottom: 10px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      margin: 10px 0;
    }

    .menu-item img {
      max-width: 100px;
      margin-right: 10px;
    }

    .image-text {
      color: black;
      font-weight: bold;
    }
	/* Add this CSS block to your existing styles */
.food-card {
  border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin: 10px;
    width: 340px; /* Adjusted width to accommodate the image width */
    height: 200px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    margin-bottom: 40px;
    display: flex;
    flex-direction: column;
}

.food-card h2 {
    font-size: 20px;
    width: :123px;
    color: #333;
    margin-left: 170px;
	margin-right:70px;
}

.food-card p {
    font-size: 16px;
    color: #666;
    margin-bottom: 15px;
	
}

.food-card img {
    max-width: 100%; 
    max-height: 140px;
    height:440px;
    width: auto; 
    margin-top: -123px;
    margin-right: 195px; 
    align-self: center; 
    border-radius: 8px; 
}

.food-card button {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 15px;
    border: none;
    width: 100px;
    margin-left :170px;
    border-radius: 5px;
    cursor: pointer;
}

.food-card:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}
.card-container {
    display: flex;
    flex-wrap: wrap;
  
}

  </style>
</head>
<body>
  <header>
    <h1>Ruchira Food Delivery Express</h1>
    <p>Delicious Food Delivered to Your Doorstep</p>
  </header>
  <div>
            <?php
            if ($loggedInUserName !== 'Guest') {
                echo "Welcome, $loggedInUserName!";
            } else {
                echo "Welcome, Guest!";
            }
            ?>
        </div>
  <nav>
    <a href="#">Home</a>
    
    <a href="register.php">Register/Login</a>
    <a href="about.html">About Us</a>
    <a href="adminlogin.php">Admin</a>
    
   
  </nav>

  <!-- Time-based menu items -->
  	 <div class="menu-card"style="background:transparent";>

  <div id="time-slots">
    <div class="time-slot" id="breakfast">
      <h2>Breakfast Menu</h2>
      <div class="additional-images">
        <div class="image-container">
          <a href="#"><img src="img/mca1.png" alt="Image 1"></a>
          <a href="#"><p class="image-text" style="color:black;"><b>IDLI</b></p></a>
        </div>
        <div class="image-container">
          <a href="#"><img src="img/mca2.png" alt="Image 2"></a>
          <a href="#"><p class="image-text" style="color:black;"><b>MASALADOSA</b></p></a>
        </div>
        <div class="image-container">
          <a href="#"><img src="img/png1.webp" alt="Image 2"></a>
          <a href="#"><p class="image-text" style="color:black;"><b>VADA</b></p></a>
        </div>
        <div class="image-container">
          <a href="#"><img src="img/png2.png" alt="Image 2"></a>
          <a href="#"><p class="image-text" style="color:black;"><b>NEER DOSA</b></p></a>
        </div>
        <div class="image-container">
          <a href="#"><img src="img/png3.png" alt="Image 2"></a>
          <a href="#"><p class="image-text" style="color:black;"><b>POORI</b></p></a>
        </div>
      </div>
    </div>

    <div class="time-slot" id="lunch">
      <h2>Lunch Menu</h2>
      <div class="additional-images">
        <div class="image-container">
          <a href=""><img src="img/thali.png" alt="Image 3"></a>
          <a href=""><p class="image-text" style="color:black;"><b>LUNCH</b></p></a>
        </div>
        <div class="image-container">
          <a href=""><img src="img/v1.png" alt="Image 3"></a>
          <a href=""><p class="image-text" style="color:black;"><b>VEG BIRYANI</b></p></a>
        </div>
      </div>
    </div>

    <div class="time-slot" id="snacks">
      <h2>Snacks Menu</h2>
      <div class="additional-images">
        <div class="image-container">
          <a href=""><img src="img/mca3.png" alt="Image 3"></a>
          <a href=""><p class="image-text" style="color:black;"><b>SANDWICH</b></p></a>
        </div>
        <div class="image-container">
          <a href=""><img src="img/mca9.png" alt="Image 3"></a>
          <a href=""><p class="image-text" style="color:black;"><b>CHATS</b></p></a>
        </div>
       
      </div>
    </div>

    <div class="time-slot" id="dinner">
      <h2>Dinner Menu</h2>
      <div class="additional-images">
        <div class="image-container">
          <a href="your-link-3.html"><img src="img/mca10.png" alt="Image 3"></a>
          <a href="your-link-2.html"><p class="image-text" style="color:black;"><b>Barbeque Chicken</b></p></a>
        </div>
        <div class="image-container">
          <a href="your-link-3.html"><img src="img/mca6.png" alt="Image 3"></a>
          <a href="your-link-2.html"><p class="image-text" style="color:black;"><b> NON VEG-BIRYANI</b></p></a>
        </div>
		
		<div class="image-container">
          <a href="your-link-3.html"><img src="img/mca11.png" alt="Image 3"></a>
          <a href="your-link-2.html"><p class="image-text" style="color:black;"><b>Butter Chicken</b></p></a>
        </div>
      </div>
    </div>
  </div>
  <!-- product.php -->

  <h2>What's on your mind?</h2>
  
  <div class="additional-images">
    <!-- product.php -->

    <div class="image-container">
    <a href="product.php?category=dosa">
  <img src="img/mca2.png" alt="DOSA Image">
  <p class="image-text" style="color: black;"><b>DOSA</b></p>
</a>
	  
    </div>
    
    <div class="image-container">
    <a href="product.php?category=sandwich">
  <img src="img/mca3.png" alt="DOSA Image">
  <p class="image-text" style="color: black;"><b>SANDWICH</b></p>
</a>  
 </div>
 
  <div class="image-container">
  <a href="product.php?category=poori">
  <img src="img/mca4.png" alt="Poori Image">
  <p class="image-text" style="color: black;"><b>Poori</b></p>
</a>  
 </div>
 
 <div class="image-container">
      <a href="product.php?category=paratha"><img src="img/mca5.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>PARATHA</b></p></a>    
 </div>
 <div class="image-container">
      <a href="product.php?category=biryani"><img src="img/mca6.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>BIRYANI</b></p></a>    
 </div>
 <div class="image-container">
      <a href="product.php?category=pizza"><img src="img/pizza.png" alt="Image 3"></a>
 <a href="product.php"><p class="image-text"style="color:black"><b>PIZZA</b></p></a>  
 </div>
 </div>
 <div class="additional-images">

</div>

  <!-- JavaScript to show/hide items based on the time -->
  <script>
    // Get the current hour (0-23) from the system time
    const currentHour = new Date().getHours();

    // Function to show or hide items based on the current time
    function showItemsByTime() {
      const timeSlots = document.querySelectorAll('.time-slot');
      timeSlots.forEach((slot) => {
        const slotId = slot.id;
        if (slotId === 'breakfast' && currentHour >= 6 && currentHour < 12) {
          slot.style.display = 'block';
        } else if (slotId === 'lunch' && currentHour >= 12 && currentHour < 16) {
          slot.style.display = 'block';
        } else if (slotId === 'snacks' && currentHour >= 16 && currentHour < 20) {
          slot.style.display = 'block';
        } else if (slotId === 'dinner' && (currentHour >= 20 || currentHour < 6)) {
          slot.style.display = 'block';
        } else {
          slot.style.display = 'none';
        }
      });
    }

    // Call the function to initially show/hide items based on the time
    showItemsByTime();
  </script>
  
</body>
</html>
