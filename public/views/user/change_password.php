<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

$user_id = $_SESSION['USER']['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $original_password = htmlspecialchars($_POST['original_password']);
  $password = htmlspecialchars($_POST['password']);
  $confirm_password = htmlspecialchars($_POST['confirm_password']);

  $current_password_hash = getUserPassword($user_id);

  if (!password_verify($original_password, $current_password_hash)) {
    $errors['original_password'] = "The original password is incorrect!";
  }

  if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?\":{}|])[A-Za-z\d!@#$%^&*(),.?\":{}|]{8,}$/", $password)) {
    $errors['password'] = "The password must be at least 8 characters long, contain 1 uppercase letter, 1 digit, and 1 special character!";
  }
  if (($password !== $confirm_password)) {
    $errors['confirm_password'] = "The passwords do not match!";
  }
  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if (updateUserPassword($user_id, $hashed_password)) {
      set_message('Password successfully updated.', 'success');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
      set_message('Error updating the password.', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }
}

?>
<main>
  <?php require '../../inc/user_sidebar.php'; ?>
  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post">
      <h1>Change Password</h1>
      <h2>Secure your account with a new password.</h2>
      <div>
        <label class="login_label">
          Old password
        </label>
        <div class="password_div">
          <input value="<?= post_old_value('original_password') ?>" placeholder="" type="password" name="original_password" class="password_input input">
          <div class="password_icon">
            <i class="fa-regular fa-eye show-password" style="cursor: pointer;display: none;"></i>
            <i class="fa-regular fa-eye-slash hide-password" style="cursor: pointer;"></i>
          </div>
        </div>
      </div>
      <?= isset($errors['original_password']) ? '<div class="error">' . $errors['original_password'] . '</div>' : '' ?>
      <div class="double-input">
        <div>
          <label class="login_label">
            New password
          </label>
          <div class="password_div">
            <input class="input  password-name" value="<?= post_old_value('password') ?>" placeholder="" type="password" name="password">
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
      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Submit</button>
    </form>
  </div>
</main>
<script src="<?= ROOT ?>/js/password-signup.js" defer></script>
<script src="<?= ROOT ?>/js/password-login.js" defer></script>


</body>

</html>