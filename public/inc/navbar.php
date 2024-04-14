<?php require 'header.php';
?>

<script src="<?= ROOT ?>/script.js" defer></script>
<div class="header">
  <?php require 'logo.php' ?>
  <div>
    <ul class="nav-list">
      <li><a class="nav-link" href="<?= ROOT ?>/">Accueil</a></li>
      <li><a class="nav-link" href="<?= ROOT ?>/produits.php">Produits</a></li>
      <li><a class="nav-link" href="">Accueil</a></li>
      <li class="vr"></li>
      <li id="geolocation"><i style="padding-right: 4px;" class="fa-solid fa-location-dot fa-xs"></i></li>
      <li class="vr"></li>
      <li><i style="color:#080100" class="fa-solid fa-cart-shopping fa-lg"></i></li>
      <li><i style="color:#080100" class="fa-solid fa-heart fa-lg"></i></li>
      <!-- <li class="vr"></li> -->
      <li>
        <div class="dropdown">
          <button class="user-btn">
            <i style="color:#080100; padding-right: 6px;" class="fa-solid fa-circle-user fa-2x"></i>
          </button>
          <div class="dropdown-menu">
            <a href="<?= ROOT ?>/views/auth/login.php" class="login">Login</a>
            <div class="hr"></div>
            <a href="<?= ROOT ?>/views/auth/signup.php" class="signup">Signup</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<!-- </body> -->