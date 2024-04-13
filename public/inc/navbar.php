<?php require 'header.php';
?>

<div class="header">
  <?php require 'logo.php' ?>
  <div>
    <ul class="nav-list">
      <li><a class="nav-link" href="<?=ROOT?>/">Accueil</a></li>
      <li><a class="nav-link" href="">Produits</a></li>
      <li><a class="nav-link" href="">Accueil</a></li>
      <li class="vr"></li>
      <li>You are browsing from </li>
      <li class="vr"></li>


      <li><i style="color:#080100" class="fa-solid fa-cart-shopping fa-lg"></i></li>
      <li><i style="color:#080100" class="fa-solid fa-heart fa-lg"></i></li>
      <li>
        <div class="dropdown">
          <button class="user-btn">
            <i style="color:#080100; padding-right: 6px;" class="fa-solid fa-circle-user fa-2x"></i>
          </button>
          <div class="dropdown-menu">
            <a href="" class="login">Login</a>
            <div class="hr"></div>
            <a href="" class="signup">Signup</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<!-- </body> -->