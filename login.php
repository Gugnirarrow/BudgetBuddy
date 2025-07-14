<?php
session_start();
require_once 'db.php'; // DB 연결이 필요하면 주석 해제

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
  <title>BudgetBuddy Login</title>
</head>
<body style ="background-color: #5d5bd8;">
  <div class="login">
    <div class="overlap-wrapper">
      <div class="overlap">
        <div class="overlap-group">
          <div class="div">
            <form action="login_process.php" method="POST">
              <div class="group">
                <a href="register.php" style="text-decoration: none;">
                <div class="overlap-group-2">
                    <div class="rectangle"></div>
                    <div class="text-wrapper" style="color:white;">SIGN UP</div>
                </div>
                </a>

                <div class="div-wrapper">
                  <div class="text-wrapper-2">LOG IN</div>
                </div>
              </div>

              <div class="rectangle-2"></div>

              <label for="email" class="text-wrapper-3">*Email</label>
              <input type="email" id="email" name="email" required
                class="rectangle-3"
                placeholder="Enter your email"
                style="position: absolute; top: 329px; left: 446px; width: 583px; height: 64px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <label for="password" class="text-wrapper-4">*Password</label>
              <input type="password" id="password" name="password" required
                class="rectangle-4"
                placeholder="Enter your password"
                style="position: absolute; top: 477px; left: 446px; width: 583px; height: 65px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <button type="submit"
                class="text-wrapper-5"
                style="position: absolute; top: 600px; left: 446px; background: none; border: none;
                color: #f8f8f8; background-color: #555ccf; width: 583px; height: 64px; border-radius: 10px; font-size: 24px; font-weight: 700; cursor: pointer;">
                Log in
              </button>
            </form>

            <?php if (!empty($error)) : ?>
              <p style="color: red; position: absolute; top: 750px; left: 600px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="text-wrapper-6">Welcome back!</div>
            <p class="p">Hey buddy, let’s manage your money!</p>
            <div class="social-login-divider">
                <img src="img/line.png" alt="line" class="divider-line">
                <div class="text">or sign up with</div>
                <img src="img/line.png" alt="line" class="divider-line">
            </div>


                <div class="social-login-icons">
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

            <p class="don-t-have-an">
              <span class="span">Don’t have an account?</span>
              <span class="text-wrapper-8">&nbsp;</span>
              <a href="register.php" class="text-wrapper-9">Sign up</a>
            </p>
          </div>

          <img class="https-lottiefiles" src="img/cat-login.png" alt="Cat working" />
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
