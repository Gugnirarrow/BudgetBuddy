<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - BudgetBuddy</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body style ="background-color: #7c8af1;">
  <div class="sign-up">
    <div class="overlap-wrapper">
      <div class="overlap">
        <div class="overlap-group">
          <div class="div">
            <form action="register_process.php" method="POST">
              <div class="group">
                <div class="div-wrapper"><div class="text-wrapper">SIGN UP</div></div>
                <a href="login.php" style="text-decoration: none;">
                  <div class="overlap-group-2">
                    <div class="rectangle"></div>
                    <div class="text-wrapper-2">LOG IN</div>
                  </div>
                </a>
              </div>

              <div class="rectangle-2"></div>

              <?php
              if(isset($_SESSION['error'])){
                  echo "<p style='color:red; position:absolute; top:260px; left:450px;'>".$_SESSION['error']."</p>";
                  unset($_SESSION['error']);
              }
              if(isset($_SESSION['success'])){
                  echo "<p style='color:green; position:absolute; top:260px; left:450px;'>".$_SESSION['success']."</p>";
                  unset($_SESSION['success']);
              }
              ?>

              <label for="name" class="text-wrapper-3">*Name</label>
              <input type="text" id="name" name="name" required
                class="rectangle-3"
                placeholder="Enter your name"
                style="position: absolute; top: 320px; left: 450px; width: 580px; height: 60px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <label for="email" class="text-wrapper-5">*Email</label>
              <input type="email" id="email" name="email" required
                class="rectangle-4"
                placeholder="Enter your email"
                style="position: absolute; top: 420px; left: 450px; width: 580px; height: 60px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <label for="password" class="text-wrapper-4">*Password</label>
              <input type="password" id="password" name="password" required
                class="rectangle-5"
                placeholder="Enter your password"
                style="position: absolute; top: 520px; left: 450px; width: 580px; height: 60px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <label for="confirm_password" class="text-wrapper-20">*Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" required
                class="rectangle-5"
                placeholder="Confirm your password"
                style="position: absolute; top: 620px; left: 450px; width: 580px; height: 60px;
                border-radius: 10px; border: 0.5px solid #000; padding: 0 10px; font-size: 16px;">

              <button type="submit"
                class="text-wrapper-6"
                style="position: absolute; top: 750px; left: 450px; background: none; border: none;
                color: #f8f8f8; background-color: #1F1D70; width: 580px; height: 64px; border-radius: 10px; font-size: 24px; font-weight: 700; cursor: pointer;">
                Sign up
              </button>

              <div class="i-agree-to-become-a">
                <input type="checkbox" id="termsCheckbox" name="terms" style="width: 35px; height: 35px;">
                <label for="termsCheckbox">
                <span class="text-wrapper-11">I agree to become a Budget Buddy member and have read and accepted the </span>
                <span class="text-wrapper-12"><a href="#">Terms & Conditions</a></span>
                <span class="text-wrapper-11">.</span>
                </label>
            </div>
            </form>

            <div class="text-wrapper-6">Join Us Today!</div>
            <p class="p">Hey buddy, let’s manage your money!</p>

            <div class="social-login-divider-2">
                <img src="img/line.png" alt="line" class="divider-line">
                <div class="text">or sign up with</div>
                <img src="img/line.png" alt="line" class="divider-line">
            </div>

            <div class="social-login-icons-2">
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
              <span class="span">Already have an account?</span>
              <span class="text-wrapper-9">&nbsp;</span>
              <a href="login.php" class="text-wrapper-10">Login</a>
            </p>

            <img class="line" src="img/line-4.svg" />
            <img class="line-2" src="img/line-5.svg" />

          </div>

          <img class="https-lottiefiles" src="img/cat-signup.png" />
        </div>

        <div class="group-2">
          <div class="overlap-5">
            <div class="text-wrapper-13">udgetBuddy</div>
            <div class="group-3">
              <div class="overlap-group-3">
                <div class="text-wrapper-14">B</div>
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
