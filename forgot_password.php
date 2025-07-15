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

            $reset_link = "http://localhost:8080/WAPGroup/reset_password.php?token=".$token;

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
<html></html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
  <title>Forgot Password</title>
</head>
<body style ="background-color: #5d5bd8;">
  <div class="login">
    <div class="overlap-wrapper">
      <div class="overlap">
        <div class="overlap-group">
          <div class="div" style="left: 60px;">

            <!-- Display error or success messages -->

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
              <div class="rectangle-2"></div>

              <label for="email" class="text-wrapper-3" style="top: 380px;">*Email</label>
              <input type="email" id="email" name="email" required
                class="rectangle-3"
                placeholder="Enter your email"
                style="position: absolute; top: 410px; left: 446px; width: 583px; height: 64px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <button type="submit"
                class="text-wrapper-5"
                style="position: absolute; top: 500px; left: 446px; background: none; border: none;
                color: #f8f8f8; background-color: #555ccf; width: 583px; height: 64px; border-radius: 10px; font-size: 24px; font-weight: 700; cursor: pointer;">
                Send Reset Link
              </button>
            </form>


            <?php if (!empty($error)) : ?>
              <p style="color: red; position: absolute; top: 750px; left: 600px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="text-wrapper-6" style="top: 230px;">Forgot Password</div>
            <p class="p" style="top: 310px;">We’ll send you the updated instructions shortly.</p>
            <div class="social-login-divider" style="top: 650px;">
                <img src="img/line.png" alt="line" class="divider-line">
                <div class="text">or sign up with</div>
                <img src="img/line.png" alt="line" class="divider-line">
            </div>


                <div class="social-login-icons" style="top: 710px;">
                <a href="#">
                    <img src="img/google-icon.png" alt="Google" class="social-icon" />
                </a>
                <a href="#">
                    <img src="img/apple-icon.png" alt="Apple" class="social-icon" />
                </a>
                <a href="#">
                    <img src="img/instagram-icon.png" alt="Instagram" class="social-icon" />
                </a>
                <a href="#">
                    <img src="img/facebook-icon.png" alt="Facebook" class="social-icon" />
                </a>
                </div>

            <p class="don-t-have-an" style="top: 800px; left: 600px;">
              <span class="span">Don’t have an account?</span>
              <span class="text-wrapper-8">&nbsp;</span>
              <a href="register.php" class="text-wrapper-9">Sign up</a>
            </p>
          </div>
        </div>

        <div class="group-2">
          <div class="overlap-5">
            <div class="text-wrapper-12">udgetBuddy</div>
            <div class="group-3">
              <div class="overlap-group-3">
                <div class="text-wrapper-13">B</div>
                <img class="line-3" src="img/line-6.png" />
                <img class="line-4" src="img/line-7.png" />
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
