<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';
require 'vendor/autoload.php'; // Composer로 설치한 경우

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        // Generate token and expiry
        $token = bin2hex(random_bytes(50));
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));



        // Store in database
        $sql_update = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sss", $token, $expiry, $email);
        $stmt_update->execute();

        // Send reset link via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($_ENV['SMTP_USER'], 'BudgetBuddy Support');
            $mail->addAddress($email);

            $reset_link = "http://localhost:8080/BudgetBuddy/reset_password.php?token=".$token;

            $mail->isHTML(true);
            $mail->Subject = 'BudgetBuddy Password Reset Link';
            $mail->Body    = "Hello,<br><br>Click the link below to reset your password:<br>
                              <a href='$reset_link'>$reset_link</a><br><br>
                              This link will expire in 1 hour.<br><br>
                              Regards,<br>BudgetBuddy Team";

            $mail->send();
            $_SESSION['success'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
