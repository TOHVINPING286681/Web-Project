<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or take appropriate action
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Fetch user information, including user_id, username, and image path
$stmtUser = $pdo->prepare("SELECT user_id, user_name FROM tbl_users WHERE user_id = :user_id");
$stmtUser->bindParam(':user_id', $userID, PDO::PARAM_INT);
$stmtUser->execute();
$userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Fetch items for the specific user
$stmtItems = $pdo->prepare("SELECT item_id, item_name, item_price, item_category, image1_path FROM tbl_items WHERE user_id = :user_id");
$stmtItems->bindParam(':user_id', $userID, PDO::PARAM_INT);
$stmtItems->execute();
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCycle Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="body-profile">
    <header>
          <div class="logo-container">
            <img src="icons/logo.svg">
            <h1>TradeCycle</h1>
          </div>
          <div class="username-icon-container">
            <div class="dropdown">
                <button class="dropbtn">
                    <p class="username1"></p>
                    <i class="fas fa-user"></i>
                </button>
                <div class="dropdown-content">
                    <a href="homepage.php">Back to Homepage</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
      </header>
      <div class="user-button-container">
        <div class="user-info">
          <img src="icons/images.jpeg" alt="User avatar" class="avatar">
          <p class="username2"></p>
        </div> 
          <a href="add_item.html" class="add-item-button">Add new item +</a>
        </div>
       
      </div>
      </div>
      <div class="wrapper-container">
      <div class="item-container">
      <?php
            foreach ($items as $item) {
                echo "<div class='grid-item' data-category='{$item['item_category']}' data-price='{$item['item_price']}'>";
                echo "<img src='{$item['image1_path']}' alt='Item Image' />";
                echo "<h3>{$item['item_name']}</h3>";
                echo "<p id='mainPrice'>RM {$item['item_price']}</p>";
                echo "<button onclick=\"location.href = 'edit_item.php?item_id={$item['item_id']}';\" class='editBtn'>Edit</button>";
                echo "</div>";
            }
        ?>
        </div>
      </div>
      
  <footer>
    <p>&copy; 2023 TradeCycle. All rights reserved.</p>
  </footer>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Fetch the username using an AJAX request
      fetch('profile.php')
        .then((response) => response.json())
        .then((data) => {
          if (data.user_name) {
            // Make sure to use the correct key here
            // Update the username in the dropdown
            document.querySelector('.username1').innerText = data.user_name;
            document.querySelector('.username2').innerText = data.user_name;
          } else {
            console.error('Error fetching username:', data.error);
          }
        })
        .catch((error) => console.error('Error fetching username:', error));
    });
  </script>
</body>
</html>
