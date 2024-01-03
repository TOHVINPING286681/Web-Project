<?php
include('dbconnect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email_login'];
    $password = $_POST['pass_login'];

    $stmt = $pdo->prepare("SELECT user_id, name, user_password FROM tbl_users WHERE user_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: homepage.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password";
            header("Location: index.html");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password";
        header("Location: index.html");
        exit();
    }
}
?>
