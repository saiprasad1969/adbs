<!DOCTYPE html>
<html>
<head>
  <title>Food Delivery Website</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f7f7f7;
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
  </style>
</head>
<body>
  <header>
    <h1>Food Delivery Express</h1>
    <p>Delicious Food Delivered to Your Doorstep</p>
  </header>
  <nav>
    <a href="#">Home</a>
    <a href="#">Menu</a>
    <a href="#">Order Online</a>
    <a href="#">About Us</a>
    <a href="#">Contact</a>
  </nav>
  <!-- Advertisement banner with linked images and sliding animation -->
  <h2>What's on your mind?</h2>
  <!-- Additional images with text -->
  <div class="additional-images">
    <div class="image-container">
      <a href="your-link-1.html"><img src="img/mca1.png" alt="Image 1"></a>
      <a href="your-link-2.html"><p class="image-text"style="color:black"><b>IDLI</b></p></a>
	  
    </div>
    <div class="image-container">
      <a href="your-link-2.html"><img src="img/mca2.png" alt="Image 2"></a>
       <a href="your-link-2.html"><p class="image-text"style="color:black"><b>DOSA</b></p></a>
    </div>
    <div class="image-container">
      <a href="your-link-3.html"><img src="img/mca3.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>SANDWICH</b></p></a>    
 </div>
 
  <div class="image-container">
      <a href="your-link-3.html"><img src="img/mca4.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>POORI</b></p></a>    
 </div>
 
 <div class="image-container">
      <a href="your-link-3.html"><img src="img/mca5.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>PARATHA</b></p></a>    
 </div>
 <div class="image-container">
      <a href="your-link-3.html"><img src="img/mca6.png" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>BIRYANI</b></p></a>    
 </div>
 <div class="image-container">
      <a href="your-link-3.html"><img src="img/mca7.png.crdownload" alt="Image 3"></a>
 <a href="your-link-2.html"><p class="image-text"style="color:black"><b>PIZZA</b></p></a>  
 </div>
 </div>
 <div class="additional-images">
<div class="image-container">
    <a href="your-link-3.html"><img src="img/mca8.png" alt="Image 3"></a>
    <a href="your-link-2.html"><p class="image-text" style="color:black;"><b>CHOLE BATURE</b></p></a>
</div>
</div>
 <!-- This line break will display the next content on the next line -->

    <!-- Add more images with text as needed -->
 
</body>
</html>
