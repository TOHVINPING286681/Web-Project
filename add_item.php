<?php
session_start();

include('dbconnect.php'); // Include your database connection

// Function to handle file uploads
function handleFileUpload($fileInput, $targetDir) {
    $fileName = basename($_FILES[$fileInput]['name']);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$fileInput]['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the file to the specified directory
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES[$fileInput]['name'])) . " has been uploaded.";
            return $targetFile; // Return the path to the uploaded file
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set and not empty
    if (
        isset($_FILES['image1']) && !empty($_FILES['image1']['name']) &&
        isset($_FILES['image2']) && !empty($_FILES['image2']['name']) &&
        isset($_FILES['image3']) && !empty($_FILES['image3']['name']) &&
        isset($_POST['itemName']) && !empty($_POST['itemName']) &&
        isset($_POST['category']) && !empty($_POST['category']) &&
        isset($_POST['price']) && !empty($_POST['price']) &&
        isset($_POST['quantity']) && !empty($_POST['quantity']) &&
        isset($_POST['description']) && !empty($_POST['description']) &&
        isset($_POST['pickup_option']) && !empty($_POST['pickup_option'])
    ) {
        // Collect form data
        $itemName = $_POST['itemName'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $pickupOption = $_POST['pickup_option'];

        // Here, include logic to get the user ID (for example, assuming it's stored in the session)
        $userID = $_SESSION['user_id']; // Adjust this based on your actual session structure

        // Prepare and execute the SQL query to insert data into tbl_items
        $stmt = $pdo->prepare("INSERT INTO tbl_items (user_id, item_name, item_category, item_price, item_quantity, item_desc, item_location, item_pickup) 
                               VALUES (:userID, :itemName, :category, :price, :quantity, :description, :location, :pickupOption)");

        // Assuming 'location' is the name of the location input field in the HTML form
        $location = isset($_POST['location']) ? $_POST['location'] : '';

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':itemName', $itemName);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':pickupOption', $pickupOption);

        try {
            $stmt->execute();

            // Handle file uploads for each image input
            $image1Path = handleFileUpload('image1', 'assets/items/');
            $image2Path = handleFileUpload('image2', 'assets/items/');
            $image3Path = handleFileUpload('image3', 'assets/items/');

            // Redirect to a success page or perform any other action after successful item addition
            header("Location: homepage.html?success=Item added successfully!");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required!";
    }
}
?>
