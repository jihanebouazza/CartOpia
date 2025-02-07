<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$errors = [];
$id = $_GET['id'] ?? 0;
$user = getUserByID($id);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $id = htmlspecialchars($_POST['id']);
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $phone_number = htmlspecialchars($_POST['phone_number']);
  $address = htmlspecialchars($_POST['address']);
  $city = htmlspecialchars($_POST['city']);
  $postal_code = htmlspecialchars($_POST['postal_code']);
  $role = htmlspecialchars($_POST['role']);

  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($first_name))) {
    $errors['first_name'] = "First name is invalid!";
  }
  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($last_name))) {
    $errors['last_name'] = "Last name is invalid!";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "The email format is invalid!";
  }

  if (!preg_match("/^(06|07|\+212)(\s)?[0-9]{8}$/", $phone_number)) {
    $errors['phone_number'] = "The phone number must start with 06, 07, or +212 followed by 8 digits.";
  }

  if (!preg_match("/^[\w\s.,'éàèùâêîôûäëïöüç-]{10,}$/", $address)) {
    $errors['address'] = "The address is invalid.";
  }

  if (!preg_match("/^[a-zA-Z]{3,}$/", $city)) {
    $errors['city'] = "The city must contain at least 3 letters.";
  }

  if (!preg_match("/^[0-9]{5}$/", $postal_code)) {
    $errors['postal_code'] = "The postal code must contain exactly 5 digits.";
  }

  if (empty($role)) {
    $errors['role'] = "The role is invalid!";
  }

  if (empty($errors)) {
    if (updateUserDetailsAdmin($id, $first_name, $last_name, $email, $phone_number, $address, $city, $postal_code, $role)) {
      set_message("The user has been successfully updated!", "success");
      redirect('views/admin/users');
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center">
    <form class="settings-form" method="post" enctype="multipart/form-data">
      <h1>Edit a user</h1>
      <h2>Update the user details as needed.</h2>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="double-input">
        <div>
          <div class="flex">
            <label class="login_label">
              First name
            </label>
          </div>
          <input value="<?= post_old_value('first_name') ? post_old_value('first_name') : $user['firstname'] ?>" placeholder="" type="text" name="first_name" class="input">
        </div>
        <div>
          <label class="login_label">
            Last name
          </label>
          <input value="<?= post_old_value('last_name') ? post_old_value('last_name') : $user['lastname'] ?>" placeholder="" type="text" name="last_name" class="input">
        </div>
      </div>

      <?= isset($errors['first_name']) ? '<div class="error">' . $errors['first_name'] . '</div>' : '' ?>
      <?= isset($errors['last_name']) ? '<div style="margin-top: 4px" class="error">' . $errors['last_name'] . '</div>' : '' ?>
      <div>
        <label class="login_label">
          Email
        </label>
        <input value="<?= post_old_value('email') ? post_old_value('email') :  $user['email'] ?>" placeholder="" type="text" name="email" class="input">
      </div>
      <?= isset($errors['email']) ? '<div class="error">' . $errors['email'] . '</div>' : '' ?>
      <div>
        <label class="label">
          Phone number
        </label>
        <input value="<?= post_old_value('phone_number') ? post_old_value('phone_number') : $user['phone_number'] ?>" placeholder="" type="text" name="phone_number" class="input">
      </div>
      <?= isset($errors['phone_number']) ? '<div class="error">' . $errors['phone_number'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Address
        </label>
        <textarea placeholder="" name="address" class="input"><?= post_old_value('address') ? post_old_value('address') : $user['address']  ?></textarea>
      </div>
      <?= isset($errors['address']) ? '<div class="error">' . $errors['address'] . '</div>' : '' ?>

      <div class="double-input">
        <div>
          <div class="flex">
            <label class="label">
              City
            </label>
          </div>
          <input value="<?= post_old_value('city') ? post_old_value('city') :  $user['city']   ?>" placeholder="" type="text" name="city" class="input">
        </div>
        <div>
          <label class="label">
            Postal code
          </label>
          <input value="<?= post_old_value('postal_code') ? post_old_value('postal_code') : $user['postal_code'] ?>" placeholder="" type="text" name="postal_code" class="input">
        </div>
      </div>
      <?= isset($errors['city']) ? '<div class="error">' . $errors['city'] . '</div>' : '' ?>
      <?= isset($errors['postal_code']) ? '<div style="margin-top: 4px" class="error">' . $errors['postal_code'] . '</div>' : '' ?>
      <div>
        <label class="login_label">
          Role
        </label>
        <select name="role" class="input">
          <option value="">Veuillez sélectionner un rôle</option>
          <option value="admin" <?= post_old_value('role') == 'admin' || $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="user" <?= post_old_value('role') == 'user' || $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
        </select>
      </div>
      <?= isset($errors['role']) ? '<div class="error">' . $errors['role'] . '</div>' : '' ?>
      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Edit</button>
    </form>
</main>
</body>

</html>