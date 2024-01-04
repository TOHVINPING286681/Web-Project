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
    />    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
          <form
            name="addItemForm"
            action="add_item.php"
            class="add-item-form"
            method="POST"
            enctype="multipart/form-data"
          >
        <div class="input-add-container">
          <label for="itemName">Item name</label>
          <input type="text" id="itemName" name="itemName" class="wide-input" />
        </div>
        <div class="input-add-container">
          <label for="category">Category</label>
          <!-- Dropdown select for category -->
          <select id="category" name="category" class="wide-input">
            <option value="Accessories">Accessories</option>
            <option value="Computer">Computer</option>
            <option value="Women Fashion">Women Fashion</option>
            <option value="Men Fashion">Men Fashion</option>
            <option value="Mobile Phone">Mobile Phone</option>
            <option value="Beauty & Personal Care">
              Beauty & Personal Care
            </option>
            <option value="Health & Equipment">Health & Equipment</option>
            <option value="Baby & Kids">Baby & Kids</option>
            <option value="Pet Supplies">Pet Supplies</option>
            <option value="Furniture & Home Living">
              Furniture & Home Living
            </option>
            <option value="Gadgets">Gadgets</option>
            <option value="Photography">Photography</option>
            <option value="Sports Equipment">Sports Equipment</option>
            <option value="Books & Stationaries">Books & Stationaries</option>
            <option value="Toys">Toys</option>
            <option value="Car & Motorbikes Accessories">
              Car & Motorbikes Accessories
            </option>
            <option value="Home Appliances">Home Appliances</option>
            <option value="Others">Others</option>
            <!-- ... (other category options) -->
          </select>
          <div class="input-row">
            <label for="price">Price</label>
            <input type="text" id="price" name="price" class="wide-input" />
          </div>
          <div class="input-row">
            <label for="quantity">Quantity</label>
            <input
              type="text"
              id="quantity"
              name="quantity"
              class="wide-input"
            />
          </div>
        </div>
        <div class="input-add-container">
          <label for="description">Description of item</label>
          <textarea
            id="description"
            name="description"
            class="wide-input"
          ></textarea>
        </div>
        <div class="input-add-container location-container">
          <label for="location">Location</label>
          <input type="text" id="location" />
          <div class="radio-group">
            <label for="pickUp">Pick Up</label>
            <input
              type="radio"
              id="pickUp"
              name="pickup_option"
              value="Pick-up"
            />
            <label for="delivery">Delivery</label>
            <input
              type="radio"
              id="delivery"
              name="pickup_option"
              value="Delivery"
            />
          </div>
        </div>
        <div class="ud-container">
    <button type="submit" class="update-item-button">Update Item</button>
   <div class="dlt-button">
   <i class="bi bi-trash-fill"></i>
   </div>
</div>


      </form>
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
    </script>
  </body>
  </html>
