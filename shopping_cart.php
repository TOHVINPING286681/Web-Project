<?php
// Include your database connection
include('dbconnect.php');

// Fetch cart items for the current user (assuming you're using sessions)
session_start();
if (!isset($_SESSION['user_id'])) {
    // Handle if user is not logged in
    echo "Please log in to view your cart";
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch items from tbl_carts for the current user
$stmt = $pdo->prepare("SELECT * FROM tbl_carts WHERE user_id = :userId");
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    <main class="add-cart-main">
    <div>
        <h1>My Cart</h1>
    </div>
    <div class="item-table-container">
        <table>
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>#</th>
                    <th>Picture</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Item Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grandTotal = 0;
                foreach ($cartItems as $index => $item):
                    $itemTotal = $item['cart_price'] * $item['cart_qty'];
                    $grandTotal += $itemTotal;
                ?>
                   <tr data-cart-id="<?php echo $item['cart_id']; ?>">
    <td><?php echo $index + 1; ?></td>
    <td><img src="<?php echo $item['image1_path']; ?>" alt="Item image" height="250" width="250"></td>
    <td><?php echo $item['item_name']; ?></td>
    <td>RM <?php echo $item['cart_price']; ?></td>
    <td class="quantity-button">
        <button onclick="updateQty(<?php echo $index; ?>, -1)">-</button>
        <span id="quantity-<?php echo $index; ?>"><?php echo $item['cart_qty']; ?></span>
        <button onclick="updateQty(<?php echo $index; ?>, 1)">+</button>
    </td>
    <td id="item-total-<?php echo $index; ?>">RM <?php echo $itemTotal; ?></td>
    <td>
    <!-- Use Font Awesome trash icon -->
    <button onclick="deleteItem(<?php echo $item['cart_id']; ?>)">
        <i class="fas fa-times-circle"></i>
    </button>
</td>
</tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5"></td>
                    <td id="grand-total">RM <?php echo $grandTotal; ?></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="check-out-button">
    <a href="checkout.html">Check Out</a>
</button>
    </div>
</main>
    <footer>
      <p>&copy; 2023 TradeCycle. All rights reserved.</p>
    </footer>

    <script>
            document.addEventListener('DOMContentLoaded', function () {
    var quantityButtons = document.querySelectorAll('.quantity-button button');
    quantityButtons.forEach(function (button, index) {
        button.addEventListener('click', function () {
            var action = button.textContent;
            var quantityElement = button.parentElement.querySelector('span');
            var currentQuantity = parseInt(quantityElement.textContent);

            if (action === '+') {
                currentQuantity++;
            } else if (action === '-') {
                if (currentQuantity > 1) {
                    currentQuantity--;
                }
            }

            quantityElement.textContent = currentQuantity;
            var row = button.closest('tr'); // Find the closest row to the button
            updateItemTotal(row.rowIndex - 1, currentQuantity); // Pass the row index and current quantity to the update function
        });
    });
});

// Function to update the item total when quantity changes
function updateItemTotal(index, quantity) {
    var cartPrices = <?php echo json_encode(array_column($cartItems, 'cart_price')); ?>; // Array of cart prices
    var quantityElements = document.querySelectorAll('.quantity-button span');
    var itemTotalElements = document.querySelectorAll('tbody tr td:nth-child(6)'); // Select all item total cells

    var grandTotal = 0;

    quantityElements.forEach(function(element, i) {
        var currentQty = parseInt(element.textContent);
        var itemTotalElement = itemTotalElements[i];
        var itemTotal = cartPrices[i] * currentQty;

        // Update the item total in the table
        itemTotalElement.textContent = 'RM ' + itemTotal;

        if (index === i) {
            // Update quantity only for the specific row
            element.textContent = quantity;
        }

        grandTotal += itemTotal; // Update the grand total
    });

    var grandTotalElement = document.getElementById('grand-total');
    grandTotalElement.textContent = 'RM ' + grandTotal;
}

function deleteItem(cartId) {
            if (confirm('Are you sure you want to delete this item from your cart?')) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var row = document.querySelector('tr[data-cart-id="' + cartId + '"]');
                            if (row) {
                                row.remove();
                                updateGrandTotal();
                            }
                        } else {
                            console.error('Error:', xhr.status);
                        }
                    }
                };
                xhr.open('POST', 'delete_cart_item.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('cart_id=' + cartId);
            }
        }



    </script>
</body>
</html>
