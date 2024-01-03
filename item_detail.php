<?php
include('dbconnect.php'); // Include your database connection

if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    // Fetch item details based on the item ID
    $stmt = $pdo->prepare("SELECT * FROM tbl_items WHERE item_id = :itemId");
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Handle case when item ID is not provided
    echo "Item ID not specified";
    exit(); // Terminate script or redirect to an error page
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TradeCycle - Homepage</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
  </head>
  <body>
    <header>
      <div class="logo-container">
        <img src="icons/logo.svg" height="100" width="100" />
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
            <a href="#">Logout</a>
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
    <main class="item-details-main">
    <div class="item-details-container">
            <?php if ($item): ?>
                <div class="item-details-pic">
                    <!-- Item details display -->
                    <div class="pic-container">
                    <img src="<?php echo $item['image1_path']; ?>" alt="Item Image">
                    </div>
                    <div class="pic-container">
                    <img src="<?php echo $item['image2_path']; ?>" alt="Item Image">
                    </div>                    
                    <div class="pic-container">
                    <img src="<?php echo $item['image3_path']; ?>" alt="Item Image">
                    </div>                 
                  </div>  
                    <div class="item-details-namebutton">
                    <h2><?php echo $item['item_name']; ?></h2>
                    <form action="add_to_cart.php" method="POST">
                      <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                      <input type="hidden" name="item_name" value="<?php echo $item['item_name']; ?>">
                      <input type="hidden" name="item_price" value="<?php echo $item['item_price']; ?>">
                      <input type="hidden" name="cart_qty" value="1">
                      <!-- Other hidden inputs if needed -->
                    <button type="submit" class="add-to-cart-btn">Add to Cart +</button>
                    </form>
                   </div>
                    <div class="item-details-desc">
                    <p>Price: RM <?php echo $item['item_price']; ?></p>
                    <p>Quantity: <?php echo $item['item_quantity']; ?></p>
                    <p>Description: <?php echo $item['item_desc']; ?></p>
                    <!--  -->
                    <!-- <form action="add_to_cart.php" method="POST">
                      <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                      <input type="hidden" name="item_name" value="<?php echo $item['item_name']; ?>">
                      <input type="hidden" name="item_price" value="<?php echo $item['item_price']; ?>">
                      <input type="hidden" name="cart_qty" value="1">
                      
                      <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                  </form> -->
                </div>
            <?php else: ?>
                <p>Item not found</p>
            <?php endif; ?>
        </div> 
    </main>

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
            } else {
              console.error('Error fetching username:', data.error);
            }
          })
          .catch((error) => console.error('Error fetching username:', error));

         const itemDetailsContainer = document.querySelector('.item-details-container');
            itemDetailsContainer.addEventListener('click', function (event) {
                const addToCartBtn = event.target.closest('.add-to-cart-btn');
                if (addToCartBtn) {
                    const itemId = addToCartBtn.dataset.itemId;
                    const itemPrice = addToCartBtn.dataset.itemPrice;
                    addToCart(itemId, itemPrice);
                }
            });
        });
      
      // function addToCart(itemId, itemPrice) {
      //   // Assuming you'd perform an AJAX request to add the item to the cart
      //   // You can use Fetch API or XMLHttpRequest here to send data to add_to_cart.php
      //   // For simplicity, here's a sample using Fetch API

      //   fetch(`add_to_cart.php?item_id=${itemId}&cart_price=${itemPrice}&cart_qty=1`, {
      //       method: 'POST',
      //       // Additional headers or body if needed
      //   })
      //   .then(response => {
      //       // Handle the response or perform further actions if required
      //       console.log('Item added to cart!');
      //   })
      //   .catch(error => {
      //       console.error('Error adding item to cart:', error);
      //   });
    
    </script>
  </body>
  </html>
