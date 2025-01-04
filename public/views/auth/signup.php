<?php
require '../../inc/header.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $confirm_password = htmlspecialchars($_POST['confirm_password']);

  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($first_name))) {
    $errors['first_name'] = "First name is invalid!";
  }
  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($last_name))) {
    $errors['last_name'] = "Last name is invalid!";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "The email format is invalid!";
  } else {
    if (emailExists($email) > 0) {
      $errors['email'] = "This email is already in use!";
    }
  }

  if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?\":{}|])[A-Za-z\d!@#$%^&*(),.?\":{}|]{8,}$/", $password)) {
    $errors['password'] = "The password must be at least 8 characters long, contain 1 uppercase letter, 1 digit, and 1 special character!";
  }
  if (($password !== $confirm_password)) {
    $errors['confirm_password'] = "The passwords do not match!";
  }

  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($user_id = signup($first_name, $last_name, $email, $hashed_password)) {
      if ($user = getUserByID($user_id)) {
        auth($user); // Log the user in by setting session variables
        set_message("Registration successful. You are now logged in!", "success");
        redirect('index');
      }
    }
  }
}
?>

<main class="login_container">
  <div class="login_form">
    <div class="back-home">
      <a href="<?= ROOT ?>/">
        <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
        Back to the homepage
      </a>
    </div>
    <form method="post">
      <h1>Join us!</h1>
      <h2>Sign up to enjoy all our services.</h2>
      <div class="double-input">
        <div>
          <div class="flex">
            <label class="login_label">
              First name
            </label>
          </div>
          <input value="<?= post_old_value('first_name') ?>" placeholder="" type="text" name="first_name" class="input">
        </div>
        <div>
          <label class="login_label">
            Last name
          </label>
          <input value="<?= post_old_value('last_name') ?>" placeholder="" type="text" name="last_name" class="input">
        </div>
      </div>

      <?= isset($errors['first_name']) ? '<div class="error">' . $errors['first_name'] . '</div>' : '' ?>
      <?= isset($errors['last_name']) ? '<div style="margin-top: 4px" class="error">' . $errors['last_name'] . '</div>' : '' ?>
      <div>
        <label class="login_label">
          Email
        </label>
        <input value="<?= post_old_value('email') ?>" placeholder="" type="text" name="email" class="input">
      </div>
      <?= isset($errors['email']) ? '<div class="error">' . $errors['email'] . '</div>' : '' ?>
      <div class="double-input">
        <div>
          <label class="login_label">
            Password
          </label>
          <div class="password_div">
            <input class="input password-name" value="<?= post_old_value('password') ?>" placeholder="" type="password" name="password">
          </div>
        </div>
        <div>
          <label class="login_label">
            Confirm password
          </label>
          <div class="password_div">
            <input class="input" type="password" value="<?= post_old_value('confirm_password') ?>" placeholder="" name="confirm_password" name="password">
          </div>
        </div>
      </div>
      <?= isset($errors['password']) ? '<div class="error">' . $errors['password'] . '</div>' : '' ?>
      <?= isset($errors['confirm_password']) ? '<div class="error">' . $errors['confirm_password'] . '</div>' : '' ?>

      <div class="password-validation">
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Digit</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Uppercase letter</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Special character</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 8 Characters</div>
      </div>
      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Sign up</button>
      <p class="underline" href="">Already have an account?<a href="<?= ROOT ?>/views/auth/login.php" style="color: #EE786B;"> Log in</a></p>
      <div style="height: 100%; position: relative;">
        <footer style="position: absolute; top: 24px; left: 72px">
          <hr />
          <p style="padding-top: 12px;"> &copy; 2024 <?= APP_NAME ?>. All rights reserved. </p>
          <p style="padding-top: 4px;"><a style="color: #615959;" href="https://storyset.com/web">Web illustrations by Storyset</a></p>
        </footer>
      </div>
    </form>
  </div>
  <div class="login_side">
    <div class="login_img_bg">
      <img src="<?= ROOT ?>/images/signup-imageT.png" alt="">
      <h2 style="text-align: center;">Sign up <br> for exclusive benefits!</h2>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/password-signup.js" defer></script>


</body>

</html>