<?php
include('dbconnect.php'); // Include your database connection

// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get necessary data from the form or AJAX request
    $itemId = $_POST['item_id']; // Item ID
    $cartQty = $_POST['cart_qty']; // Cart quantity
    $cartDate = date('Y-m-d H:i:s'); // Current date

    // Fetch item details based on the item ID
    $stmt = $pdo->prepare("SELECT * FROM tbl_items WHERE item_id = :itemId");
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $itemName = $item['item_name'];
        $itemPrice = $item['item_price'];
        $imagePath = $item['image1_path'];

        // Check if user is logged in and has a valid session
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id']; // Use the user ID from the session

            // Calculate the cart price based on the item price and quantity
            $cartPrice = $itemPrice * $cartQty;

            // Insert into tbl_carts
            $stmt = $pdo->prepare("INSERT INTO tbl_carts (item_id, item_name, item_price, cart_price, cart_qty, image1_path, user_id, cart_date) VALUES (:itemId, :itemName, :itemPrice, :cartPrice, :cartQty, :imagePath, :userId, :cartDate)");
            $stmt->bindParam(':itemId', $itemId);
            $stmt->bindParam(':itemName', $itemName);
            $stmt->bindParam(':itemPrice', $itemPrice);
            $stmt->bindParam(':cartPrice', $cartPrice);
            $stmt->bindParam(':cartQty', $cartQty);
            $stmt->bindParam(':imagePath', $imagePath);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':cartDate', $cartDate);
            $stmt->execute();

            // Check if the insertion was successful
            if ($stmt->rowCount() > 0) {
                // Store success message in session
                session_start();
                $_SESSION['success_message'] = "Item added to cart successfully";
                
                // Redirect to homepage.php
                header("Location: homepage.php");
                exit(); // Ensure script stops execution after redirection
            } else {
                echo "Failed to add item to cart";
            }
        } else {
            echo "User not logged in"; // Handle if user is not logged in
        }
    } else {
        echo "Item not found"; // Handle if item details are not fetched
    }
} else {
    echo "Invalid request method";
}
?>
