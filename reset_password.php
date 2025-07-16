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
<html></html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
  <title>Forgot Password</title>

  <style>
    .popup {
      position: fixed;
      top: 40%;
      left: 50%;
      width: 400px;
      height: 200px;
      transform: translate(-50%, -50%);
      background-color: #C6C6FF;
      border: 2px solid #5d5bd8;
      padding: 70px 30px;
      border-radius: 10px;
      z-index: 9999;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      text-align: center;
      color: black;
    }
    .popup button {
      margin-top: 20px;
      font-size: 16px;
      padding: 8px 15px;
      background-color: white;
      color: black;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body style ="background-color: #5d5bd8;">
  <div class="login">
    <div class="overlap-wrapper">
      <div class="overlap">
        <div class="overlap-group">
          <div class="div" style="left: 60px;">

              <div class="rectangle-2"></div>

            <form action="reset_password_process.php" method="POST">
              <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
              <label for="password" class="text-wrapper-3" style="top: 380px; width: 120px;">*New password</label>
              <input type="password" id="password" name="password" required
                class="rectangle-3"
                placeholder="Enter new password"
                style="position: absolute; top: 410px; left: 446px; width: 583px; height: 64px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <button type="submit"
                class="text-wrapper-5"
                style="position: absolute; top: 500px; left: 446px; background: none; border: none;
                color: #f8f8f8; background-color: #1F1D70; width: 583px; height: 64px; border-radius: 10px; font-size: 24px; font-weight: 700; cursor: pointer;">
                Change Password
              </button>
            </form>


            <div class="text-wrapper-6" style="top: 230px;">Reset Password!</div>
            <p class="p" style="top: 310px; left: 525px;">You can change your password! Enter new password!</p>
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

  <?php if (isset($_SESSION['error'])) : ?>
  <div class="popup">
    <p><?php echo $_SESSION['error']; ?></p>
    <button onclick="closePopup()">Close</button>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])) : ?>
  <div class="popup">
    <p><?php echo $_SESSION['success']; ?></p>
    <button onclick="closePopup()">Close</button>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

  <script>
  function closePopup() {
    document.querySelectorAll('.popup').forEach(popup => popup.style.display = 'none');
  }
  </script>
</body>
</html>
