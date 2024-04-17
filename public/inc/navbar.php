<?php require 'header.php';
?>

<script src="<?= ROOT ?>/js/geolocation.js" defer></script>
<script src="<?= ROOT ?>/js/dropdown.js" defer></script>
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
      <li><button class="icon-button"><i style="color: #7C6F6E;" class="fa-solid fa-cart-shopping fa-2x"></i></button></li>
      <li><button class="icon-button"><i style="color: #7C6F6E;" class="fa-solid fa-heart fa-2x"></i></button></li>
      <!-- <li class="vr"></li> -->
      <li>
        <div class="dropdown">
          <button class="user-btn icon-button">
            <i style=" color: #7C6F6E;" class="fa-solid fa-circle-user fa-2x"></i>
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