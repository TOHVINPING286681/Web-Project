<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tradecycle_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['passa'];

    // Sanitize inputs to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $username = mysqli_real_escape_string($conn, $username);
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Hash the password (using a secure hashing algorithm)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO tbl_users (name, user_name, user_email, user_phone, user_password)
    VALUES ('$name', '$username', '$email', '$phone', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful, you can redirect to a login page or perform any necessary action
        header("Location: index.html");
        exit();
    } else {
        // Handle registration failure (e.g., duplicate entry, database error)
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
