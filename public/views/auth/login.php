<?php
require '../../inc/header.php';
$errors = [];
global $con;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  if ($stmt = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1")) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row['password'])) {
        // Authenticate user
        auth($row);
        set_message('Vous êtes maintenant connecté(e).', 'success');
        redirect('index');
      } else {
        set_message("Identifiant ou mot de passe incorrect!", "error");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    } else {
      set_message("Identifiant ou mot de passe incorrect!", "error");
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
    $stmt->close();
  }
}
?>


<main class="login_container">
  <div class="login_side">
    <div class="login_img_bg">
      <img src="<?= ROOT ?>/images/login_img.png" alt="">
      <h2 style="text-align: center;">Ne tardez pas, achetez maintenant !</h2>
    </div>
  </div>
  <div class="login_form">
    <div class="back-home">
      <a href="<?= ROOT ?>/">
        <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
        Retour à la page d'accueil
      </a>
    </div>
    <form class="" method="post">
      <h1>Bienvenue de nouveau !</h1>
      <h2>Veuillez vous connecter pour continuer.</h2>
      <div>
        <label class="login_label">
          Email
        </label>
        <input value="" placeholder="" type="text" name="email" class="input">
      </div>
      <div>
        <label class="login_label">
          Mot de passe
        </label>
        <div class="password_div">
          <input value="" placeholder="" type="password" name="password" class="password_input input">
          <div class="password_icon">
            <i class="fa-regular fa-eye show-password" style="cursor: pointer;display: none;"></i>
            <i class="fa-regular fa-eye-slash hide-password" style="cursor: pointer;"></i>
          </div>
        </div>
        <a class="forgot_password" href="">Mot de passe oublier?</a>
      </div>
      <button class="primary-btn" style="width: 100%;" type="submit">Se connecter</button>
      <p class="underline" href="">Vous n'avez pas de compte ?<a href="<?= ROOT ?>/views/auth/signup.php" style="color: #EE786B;"> S'inscrire</a></p>
    </form>
  </div>
</main>
<script src="<?= ROOT ?>/js/password-login.js" defer></script>

</body>

</html>