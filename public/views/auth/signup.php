<!-- Explorez Notre Monde de Produits
Économisez gros aujourd'hui !
Économisez dès maintenant !
Inscrivez-vous pour des avantages exclusifs !
Ne tardez pas, achetez maintenant !
-->

<?php
require '../../inc/header.php';
?>

<main class="login_container">

  <div class="login_form">
    <div class="back-home">
      <a href="<?= ROOT ?>/">
        <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
        Retour à la page d'accueil
      </a>
    </div>
    <form class="" method="post">
      <h1>Rejoignez-nous !</h1>
      <h2>Inscrivez-vous pour profiter de tous nos services.</h2>
      <div class="double-input">
        <div>
          <label class="login_label">
            Prénom
          </label>
          <input value="" placeholder="" type="text" name="fist_name" class="">
        </div>
        <div>
          <label class="login_label">
            Nom
          </label>
          <input value="" placeholder="" type="text" name="last_name" class="">
        </div>
      </div>

      <div>
        <label class="login_label">
          Email
        </label>
        <input value="" placeholder="" type="text" name="email" class="">
      </div>
      <div>
        <label class="login_label">
          Numéro de téléphone
        </label>
        <input value="" placeholder="" type="text" name="email" class="">
      </div>
      <div class="double-input" style="margin-bottom: 16px;">
        <div>
          <label class="login_label">
            Mot de passe
          </label>
          <div class="password_div">
            <input value="" placeholder="" type="password" name="password">
          </div>
        </div>
        <div>
          <label class="login_label">
            Confirmer le mot de passe
          </label>
          <div class="password_div">
            <input value="" placeholder="" type="confirm_password" name="password">
          </div>
        </div>
      </div>
      <button class="primary-btn" style="width: 100%;" type="submit">Se connecter</button>
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

</body>

</html>