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

session_start(); // Start session to manage user sessions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = $_POST['email_login'];
    $password = $_POST['pass_login'];

    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Fetch hashed password from the database based on the email
    $sql = "SELECT user_id, user_password FROM tbl_users WHERE user_email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, verify the password
        $row = $result->fetch_assoc();
        $hashed_password = $row['user_password'];

        // Verify the password using password_verify()
        if (password_verify($password, $hashed_password)) {
            // Password matches, set session variables and redirect
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['name'];

            // Redirect to dashboard or profile page upon successful login
            header("Location: homepage.html");
            exit();
        } else {
            // Invalid password, show error message or redirect to login page with an error flag
            $_SESSION['login_error'] = "Invalid email or password";
            header("Location: index.html");
            exit();
        }
    } else {
        // No user found with the given email
        $_SESSION['login_error'] = "Invalid email or password";
        header("Location: index.html");
        exit();
    }
}

// Close the connection
$conn->close();
?>
