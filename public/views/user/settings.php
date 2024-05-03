<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

$user_id = $_SESSION['USER']['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);

  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($first_name))) {
    $errors['first_name'] = "Prénom invalide!";
  }
  if (!preg_match("/^[a-zA-Z]{3,}$/", trim($last_name))) {
    $errors['last_name'] = "Nom invalide!";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Le format de l'email est invalide!";
  } else {
    // Check for email uniqueness
    $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
      $errors['email'] = "Cet email est déjà utilisé!";
    }
  }

  if (empty($errors)) {
    if (updateUserProfile($user_id, $first_name, $last_name, $email)) {
      set_message('Votre profil a été mis à jour avec succès.', 'success');
      header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
    } else {
      set_message('Une erreur est survenue lors de la mise à jour de votre profil. Veuillez réessayer', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page

    }
  }
}

?>
<main>
  <?php require '../../inc/user_sidebar.php'; ?>
  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post">
      <h1>Paramètres du compte</h1>
      <h2>Gérez vos informations personnelles et vos préférences.</h2>
      <div class="double-input">
        <div>
          <div class="flex">
            <label class="login_label">
              Prénom
            </label>
          </div>
          <input value="<?= post_old_value('first_name') ? post_old_value('first_name') : user('firstname') ?>" placeholder="" type="text" name="first_name" class="input">
        </div>
        <div>
          <label class="login_label">
            Nom
          </label>
          <input value="<?= post_old_value('last_name') ? post_old_value('last_name') : user('lastname') ?>" placeholder="" type="text" name="last_name" class="input">
        </div>
      </div>

      <?= isset($errors['first_name']) ? '<div class="error">' . $errors['first_name'] . '</div>' : '' ?>
      <?= isset($errors['last_name']) ? '<div style="margin-top: 4px" class="error">' . $errors['last_name'] . '</div>' : '' ?>
      <div>
        <label class="login_label">
          Email
        </label>
        <input value="<?= post_old_value('email') ? post_old_value('email') : user('email') ?>" placeholder="" type="text" name="email" class="input">
      </div>
      <?= isset($errors['email']) ? '<div class="error">' . $errors['email'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Modifier</button>
    </form>
  </div>
</main>
</body>

</html>