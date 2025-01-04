<?php
require '../../inc/header.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  if ($user = login($email)) {
    if (password_verify($password, $user['password'])) {
      // Authenticate user
      auth($user);
      set_message('You are now logged in.', 'success');
      redirect('index');
    } else {
      set_message("Incorrect username or password!", "error");
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  } else {
    set_message("Incorrect email or password!", "error");
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
?>

<main class="login_container">
  <div class="login_side">
    <div class="login_img_bg">
      <img src="<?= ROOT ?>/images/login-imageT.png" alt="">
      <h2 style="text-align: center;">Don't wait, buy now!</h2>
    </div>
  </div>
  <div class="login_form">
    <div class="back-home">
      <a href="<?= ROOT ?>/">
        <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
        Back to the homepage
      </a>
    </div>
    <form class="" method="post">
      <h1>Welcome back!</h1>
      <h2>Please log in to continue.</h2>
      <div>
        <label class="login_label">
          Email
        </label>
        <input value="" placeholder="" type="text" name="email" class="input">
      </div>
      <div>
        <label class="login_label">
          Password
        </label>
        <div class="password_div">
          <input value="" placeholder="" type="password" name="password" class="password_input input">
          <div class="password_icon">
            <i class="fa-regular fa-eye show-password" style="cursor: pointer;display: none;"></i>
            <i class="fa-regular fa-eye-slash hide-password" style="cursor: pointer;"></i>
          </div>
        </div>
        <a class="forgot_password" href="">Forgot password?</a>
      </div>
      <button class="primary-btn" style="width: 100%;" type="submit">Log in</button>
      <p class="underline" href="">Don't have an account?<a href="<?= ROOT ?>/views/auth/signup.php" style="color: #EE786B;"> Sign up</a></p>
      <div style="height: 100%; position: relative;">
        <footer style="position: absolute; top: 48px; left: 72px">
          <hr />
          <p> &copy; 2024 <?= APP_NAME ?>. All rights reserved.</p>
          <p style="padding-top: 4px;"><a style="color: #615959;" href="https://storyset.com/web">Web illustrations by Storyset</a></p>
        </footer>
      </div>
    </form>
  </div>
</main>
<script src="<?= ROOT ?>/js/password-login.js" defer></script>

</body>

</html>