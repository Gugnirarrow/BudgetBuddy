<!DOCTYPE html>
<html>
<head>
    <title>Login - BudgetBuddy</title>
</head>
<body>
    <h2>Login</h2>

    <?php
    session_start();
    require_once 'db.php';

    if(isset($_SESSION['error'])){
        echo "<p style='color:red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success'])){
        echo "<p style='color:green;'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="login_process.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <p><a href="register.php">Register</a></p>
    <p><a href="forgot_password.php">Forgot Password?</a></p>
</body>
</html>
