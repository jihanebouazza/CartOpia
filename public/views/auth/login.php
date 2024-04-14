<?php
require '../../inc/header.php';
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
        <input value="" placeholder="" type="text" name="email" class="">
      </div>
      <div>
        <label class="login_label">
          Mot de passe
        </label>
        <div class="password_div">
          <input value="" placeholder="" type="password" name="password" class="password_input">
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

</body>

</html>