<?php
// Include your database connection
include('dbconnect.php');

// Check if cart_id is provided via POST
if (isset($_POST['cart_id'])) {
    $cartId = $_POST['cart_id'];

    // Prepare and execute SQL to delete item from tbl_carts
    $stmt = $pdo->prepare("DELETE FROM tbl_carts WHERE cart_id = :cartId");
    $stmt->bindParam(':cartId', $cartId);

    if ($stmt->execute()) {
        // Deletion successful
        echo json_encode(['success' => true]);
        exit();
    } else {
        // Error occurred during deletion
        echo json_encode(['success' => false, 'message' => 'Error deleting item']);
        exit();
    }
} else {
    // If cart_id is not provided
    echo json_encode(['success' => false, 'message' => 'Cart ID not provided']);
    exit();
}
?>
