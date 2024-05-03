<!-- Explorez Notre Monde de Produits
Économisez gros aujourd'hui !
Économisez dès maintenant !
Inscrivez-vous pour des avantages exclusifs !
Ne tardez pas, achetez maintenant !
-->
<?php
require '../../inc/header.php';
$errors = [];
global $con;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $confirm_password = htmlspecialchars($_POST['confirm_password']);

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

  if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?\":{}|])[A-Za-z\d!@#$%^&*(),.?\":{}|]{8,}$/", $password)) {
    $errors['password'] = "Le mot de passe doit comporter 8+ caractères, 1 majuscule, 1 chiffre, 1 caractère spécial!";
  }
  if (($password !== $confirm_password)) {
    $errors['confirm_password'] = "Les mots de passe ne correspondent pas!";
  }

  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL
    $stmt = $con->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
      $user_id = $stmt->insert_id;
      $stmt->close();

      // Fetch the newly created user data for authentication
      $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($user = $result->fetch_assoc()) {
        auth($user); // Log the user in by setting session variables
        set_message("Inscription réussie. Vous êtes maintenant connecté(e) !", "success");
        redirect('index');
      }
      //  else {
      //   $errors['auth'] = "Authentication failed after signup.";
      // }
    }
    // else {
    //   $errors['db_error'] = "Erreur de base de données: impossible d'enregistrer l'utilisateur.";
    // }
  }
}
// $_SESSION['user'] = [];
?>

<main class="login_container">
  <div class="login_form">
    <div class="back-home">
      <a href="<?= ROOT ?>/">
        <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
        Retour à la page d'accueil
      </a>
    </div>
    <form method="post">
      <h1>Rejoignez-nous !</h1>
      <h2>Inscrivez-vous pour profiter de tous nos services.</h2>
      <div class="double-input">
        <div>
          <div class="flex">
            <label class="login_label">
              Prénom
            </label>
          </div>
          <input value="<?= post_old_value('first_name') ?>" placeholder="" type="text" name="first_name" class="input">
        </div>
        <div>
          <label class="login_label">
            Nom
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
      <!-- <div>
        <label class="login_label">
          Numéro de téléphone
        </label>
        <input value="" placeholder="" type="text" name="phone_number" class="input">
      </div> -->
      <div class="double-input">
        <div>
          <label class="login_label">
            Mot de passe
          </label>
          <div class="password_div">
            <input class="input" value="<?= post_old_value('password') ?>" placeholder="" type="password" name="password">
          </div>
        </div>
        <div>
          <label class="login_label">
            Confirmer le mot de passe
          </label>
          <div class="password_div">
            <input class="input" type="password" value="<?= post_old_value('confirm_password') ?>" placeholder="" name="confirm_password" name="password">
          </div>
        </div>
      </div>
      <?= isset($errors['password']) ? '<div class="error">' . $errors['password'] . '</div>' : '' ?>
      <?= isset($errors['confirm_password']) ? '<div class="error">' . $errors['confirm_password'] . '</div>' : '' ?>

      <div class="password-validation">
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Chiffre</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Majuscule</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 1 Caractère spéciale</div>
        <div class="password-div-error password-init"><i class="fa-solid fa-x fa-xs"></i> 8 Characters</div>
      </div>
      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Se connecter</button>
      <p class="underline" href="">Vous avez déjà un compte ?<a href="<?= ROOT ?>/views/auth/login.php" style="color: #EE786B;"> Se connecter</a></p>
    </form>
  </div>
  <div class="login_side">
    <div class="login_img_bg">
      <img src="<?= ROOT ?>/images/signup_img.png" alt="">
      <h2 style="text-align: center;">Inscrivez-vous <br>pour des avantages exclusifs !</h2>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/password-signup.js" defer></script>


</body>

</html>