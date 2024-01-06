<?php
// Include your database connection
include('dbconnect.php');

// Fetch cart items for the current user (assuming you're using sessions)
session_start();
if (!isset($_SESSION['user_id'])) {
    // Handle if the user is not logged in
    echo "Please log in to check out";
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch items from tbl_carts for the current user
$stmt = $pdo->prepare("SELECT c.*, i.item_quantity FROM tbl_carts c
                      INNER JOIN tbl_items i ON c.item_id = i.item_id
                      WHERE c.user_id = :userId");
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start a transaction
$pdo->beginTransaction();

try {
    // Update item quantities and delete items with quantity 0
    foreach ($cartItems as $item) {
        $itemId = $item['item_id'];
        $cartQuantity = $item['cart_qty'];
        $currentItemQuantity = $item['item_quantity'];

        // Calculate the new quantity after subtracting the cart quantity
        $newQuantity = $currentItemQuantity - $cartQuantity;

        if ($newQuantity <= 0) {
            // Delete item if quantity is 0
            $deleteStmt = $pdo->prepare("DELETE FROM tbl_items WHERE item_id = :itemId");
            $deleteStmt->bindParam(':itemId', $itemId);
            $deleteStmt->execute();
        } else {
            // Update item quantity
            $updateStmt = $pdo->prepare("UPDATE tbl_items SET item_quantity = :newQuantity WHERE item_id = :itemId");
            $updateStmt->bindParam(':newQuantity', $newQuantity);
            $updateStmt->bindParam(':itemId', $itemId);
            $updateStmt->execute();
        }
    }

    // Commit the transaction
    $pdo->commit();

    // Clear the user's cart after successful checkout
    $clearCartStmt = $pdo->prepare("DELETE FROM tbl_carts WHERE user_id = :userId");
    $clearCartStmt->bindParam(':userId', $userId);
    $clearCartStmt->execute();
} catch (Exception $e) {
    // Rollback the transaction on failure
    $pdo->rollBack();
    echo "Error during checkout: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file if needed -->
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body class="body-checkout">
    <header>
        <div class="logo-container">
          <img src="icons/logo.svg" />
          <h1>TradeCycle</h1>
        </div>
        <div class="username-icon-container">
          <div class="dropdown">
            <button class="dropbtn">
              <p class="username1"></p>
              <i class="fas fa-user"></i>
            </button>
            <div class="dropdown-content">
              <a href="user_profile.php">Profile</a>
              <a href="logout.php">Logout</a>
            </div>
          </div>
          <div class="cart">
            <div class="cart-icons">
              <a href="shopping_cart.php">
                  <i class="fas fa-shopping-cart"></i>
              </a>
          </div>
        </div>
      </header>
      <div class="payment-success">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Payment success!</h1>
        <p>Thank you for shopping from TradeCycle.com.</p>
        <a href="homepage.php" class="redirect-link">Return to Homepage</a>
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
