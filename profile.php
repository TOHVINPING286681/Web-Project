<?php
session_start();

include 'dbconnect.php';

if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    try {
        $sql = "SELECT user_name FROM tbl_users WHERE user_id = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json'); // Set content type to JSON
        if ($result) {
            $username = $result['user_name'];
            echo json_encode(['user_name' => $username]);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json'); // Set content type to JSON
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'User not authenticated']); // You might want to handle this case appropriately
}
?>
