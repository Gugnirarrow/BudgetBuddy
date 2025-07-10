<?php
session_start();
require_once 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        // Generate new temporary password
        $temp_password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

        // Update password in database
        $sql_update = "UPDATE users SET password = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $hashed_password, $email);
        if($stmt_update->execute()){
            $_SESSION['success'] = "Your new password is: <strong>".$temp_password."</strong><br>Please login and change it immediately.";
        } else {
            $_SESSION['error'] = "Error updating password. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }

    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - BudgetBuddy</title>
</head>
<body>
    <h2>Forgot Password</h2>

    <?php
    if(isset($_SESSION['error'])){
        echo "<p style='color:red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success'])){
        echo "<p style='color:green;'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="forgot_password.php" method="POST">
        <label>Enter your registered email:</label><br>
        <input type="email" name="email" required><br><br>

        <input type="submit" value="Reset Password">
    </form>

    <p><a href="login.php">Back to Login</a></p>
</body>
</html>
