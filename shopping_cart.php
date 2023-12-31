<?php
// Include your database connection
include('dbconnect.php');

// Fetch cart items for the current user (assuming you're using sessions)
session_start();
if (!isset($_SESSION['user_id'])) {
    // Handle if the user is not logged in
    echo "Please log in to view your cart";
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
            <a href="homepage.php">Back to Homepage</a>
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
               <!-- Inside your <table> tag -->
                <form action="checkout.php" method="post">
                    <?php foreach ($cartItems as $index => $item): ?>
                        <input type="hidden" name="item_id[]" value="<?php echo $item['item_id']; ?>">
                        <input type="hidden" name="quantity[]" value="<?php echo $item['cart_qty']; ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="check-out-button">Check Out</button>
                </form>
                </button>
        </div>
        <!-- Hidden Payment Form -->
        <div id="payment-form" style="display: none;">
            <h2>Payment Details</h2>
            <form action="process_payment.php" method="post">
                <!-- Add your payment form fields (e.g., bank details) here -->
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" name="bank_name" required>

                <label for="account_number">Account Number:</label>
                <input type="text" id="account_number" name="account_number" required>

                <!-- Add other payment form fields as needed -->

                <button type="submit">Submit Payment</button>
            </form>
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
                    document.querySelector('.username2').innerText = data.user_name;
                } else {
                    console.error('Error fetching username:', data.error);
                }
            })
            .catch((error) => console.error('Error fetching username:', error));
    });

    document.addEventListener('DOMContentLoaded', function () {
    var quantityElements = document.querySelectorAll('.quantity-button span');
    var quantityButtons = document.querySelectorAll('.quantity-button button');

    quantityElements.forEach(function (element, index) {
        var currentQuantity = parseInt(element.textContent);
        var maxQuantity = <?php echo ($cartItems[$index]['item_quantity'] > 0) ? $cartItems[$index]['item_quantity'] : 0; ?>;

        if (isNaN(currentQuantity) || currentQuantity <= 0 || currentQuantity > maxQuantity) {
            // Reset the quantity to 1 if it's 0, invalid, or exceeds the maximum
            element.textContent = 1;
        }
    });

    quantityButtons.forEach(function (button, index) {
        button.addEventListener('click', function () {
            var action = button.textContent;
            var quantityElement = button.parentElement.querySelector('span');
            var currentQuantity = parseInt(quantityElement.textContent);
            var maxQuantity = <?php echo ($cartItems[$index]['item_quantity'] > 0) ? $cartItems[$index]['item_quantity'] : 0; ?>;

            if (action === '+') {
                if (currentQuantity < maxQuantity) {
                    currentQuantity++;
                } else {
                    alert('Cannot add more than the available quantity');
                }
            } else if (action === '-') {
                if (currentQuantity > 1) {
                    currentQuantity--;
                }
            }

            quantityElement.textContent = currentQuantity;
            var row = button.closest('tr');
            updateItemTotal(row.rowIndex - 1, currentQuantity);

            
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
                    location.reload(); // Reload the page after successful deletion
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
    function showPaymentForm() {
        var paymentForm = document.getElementById('payment-form');
        paymentForm.style.display = 'block';
    }
    </script>
</body>
</html>
