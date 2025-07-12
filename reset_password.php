<?php
session_start();
require_once 'db.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];

    $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry >= NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows != 1){
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No token provided.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - BudgetBuddy</title>
</head>
<body>
    <h2>Reset Your Password</h2>

    <?php
    if(isset($_SESSION['error'])){
        echo "<p style='color:red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="reset_password_process.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
