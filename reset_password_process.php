<?php
session_start();
require_once 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $token = $_POST['token'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update password and clear token
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL
            WHERE reset_token = ? AND reset_token_expiry >= NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $token);
    if($stmt->execute() && $stmt->affected_rows == 1){
        $_SESSION['success'] = "Password has been reset. You can now login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: reset_password.php?token=".$token);
        exit();
    }
}
?>
