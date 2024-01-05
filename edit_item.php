<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbconnect.php'); // Include your database connection

// Backend logic for updating and deleting items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Delete item logic
  if (isset($_POST['delete_item']) && isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    // Fetch item details based on the item ID
    $stmt = $pdo->prepare("SELECT * FROM tbl_items WHERE item_id = :itemId");
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

     // Delete images from the folder
     $imagePaths = [
      $item['image1_path'],
      $item['image2_path'],
      $item['image3_path'],
      // Add more paths if needed
      ];

      foreach ($imagePaths as $imagePath) {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
      }

    // Delete the item from the database
    $deleteStmt = $pdo->prepare("DELETE FROM tbl_items WHERE item_id = :itemId");
    $deleteStmt->bindParam(':itemId', $itemId);
    $deleteStmt->execute();
    
    // Redirect to a page after deletion (you can customize this)
    header("Location: user_profile.php");
    exit();
  }
  // Update item logic
  if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    // Fetch item details based on the item ID
    $stmt = $pdo->prepare("SELECT * FROM tbl_items WHERE item_id = :itemId");
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
      // Get data from the form
      $itemName = $_POST['itemName'];
      $category = $_POST['category'];
      $price = $_POST['price'];
      $quantity = $_POST['quantity'];            
      $description = $_POST['description'];
      $location = isset($_POST['location']) ? $_POST['location'] : ''; // Default to empty string if not set
      $pickupOption = isset($_POST['pickup_option']) ? $_POST['pickup_option'] : ''; // Default to empty string if not set


      // Update the item in the database
      $updateStmt = $pdo->prepare("UPDATE tbl_items SET item_name = :itemName, item_category = :category, item_price = :price, item_quantity = :quantity, item_desc = :description, item_location = :location, item_pickup = :pickupOption WHERE item_id = :itemId");
      $updateStmt->bindParam(':itemId', $itemId);
      $updateStmt->bindParam(':itemName', $itemName);
      $updateStmt->bindParam(':category', $category);
      $updateStmt->bindParam(':price', $price);
      $updateStmt->bindParam(':quantity', $quantity);
      $updateStmt->bindParam(':description', $description);
      $updateStmt->bindParam(':location', $location);
      $updateStmt->bindParam(':pickupOption', $pickupOption);
      $updateStmt->execute();

      // Redirect to the item details page
      header("Location: item_detail.php?item_id=$itemId");
      exit();
    } else {
      // Handle case when item ID is not found
      echo "Item not found";
      exit();
    }
  }
}

// Fetch item details based on the item ID
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
    <title>TradeCycle</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="responsive.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <header>
      <div class="logo-container">
        <img src="icons/logo.svg"/>
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
            action="?item_id=<?php echo $item['item_id']; ?>"
            class="add-item-form"
            method="POST"
            enctype="multipart/form-data"
          >
        <div class="input-add-container">
          <label for="itemName">Item name</label>
          <input type="text" id="itemName" name="itemName" class="wide-input" value="<?php echo $item['item_name']; ?>" />
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
            <input type="text" id="price" name="price" class="wide-input" value="<?php echo $item['item_price']; ?>" />
          </div>
          <div class="input-row">
            <label for="quantity">Quantity</label>
            <input type="text" id="quantity" name="quantity" class="wide-input" value="<?php echo $item['item_quantity']; ?>" />
          </div>
        </div>
        <div class="input-add-container">
          <label for="description">Description of item</label>
          <textarea id="description" name="description" class="wide-input"><?php echo $item['item_desc']; ?></textarea>
        </div>
        <div class="input-add-container location-container">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?php echo $item['item_location']; ?>" />
          <div class="radio-group">
            <label for="pickUp">Pick Up</label>
            <input type="radio" id="pickUp" name="pickup_option" value="Pick-up" <?php echo ($item['item_pickup'] == 'Pick-up') ? 'checked' : ''; ?> />
            <label for="delivery">Delivery</label>
            <input type="radio" id="delivery" name="pickup_option" value="Delivery" <?php echo ($item['item_pickup'] == 'Delivery') ? 'checked' : ''; ?> />
        </div>
          </div>
        </div>
        <div class="ud-container">
          <button type="submit" class="update-item-button">Update Item</button>
          <!-- Using a trash icon for the delete button -->
          <input type="hidden" id="deleteItemInput" name="delete_item" value="" />
          <a href="javascript:void(0);" onclick="deleteItem()" class="delete-item-icon">
            <i class="bi bi-trash-fill"></i>
          </a>
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
        });
        // Assuming this is your JavaScript code for deletion
        function deleteItem() {
            var confirmation = confirm("Are you sure you want to delete this item?");
            if (confirmation) {
                // Assuming you have an input field with the id 'deleteItemInput'
                var deleteItemInput = document.getElementById('deleteItemInput');
                
                // Checking if the element exists before setting its value
                if (deleteItemInput) {
                    deleteItemInput.value = 'delete';
                    document.forms['addItemForm'].submit();
                }
            }
        }

    </script>
  </body> 
  </html>
