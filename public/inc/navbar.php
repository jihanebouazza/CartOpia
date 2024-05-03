<?php require 'header.php';
// $_SESSION['USER']=[];
// echo '<pre>';
// print_r($_SESSION['USER']);
// echo '</pre>';
if (isset($_POST['logout'])) {
  if (!empty($_SESSION['USER'])) {
    unset($_SESSION['USER']);
    set_message('Vous avez été déconnecté avec succès.', 'success');
    redirect('index');
  }
}
?>

<script src="<?= ROOT ?>/js/geolocation.js" defer></script>
<script src="<?= ROOT ?>/js/dropdown.js" defer></script>
<div class="header">
  <?php require 'logo.php' ?>
  <div>
    <ul class="nav-list">
      <li><a class="nav-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/index.php' ? 'active' : '' ?>" href="<?= ROOT ?>/">Accueil</a></li>
      <li><a class="nav-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/products/index.php' ? 'active' : '' ?>" href="<?= ROOT ?>/views/products/">Produits</a></li>
      <li><a class="nav-link" href="">Accueil</a></li>
      <li class="vr"></li>
      <li id="geolocation"><i style="padding-right: 4px;" class="fa-solid fa-location-dot fa-xs"></i></li>
      <li class="vr"></li>
      <li>
        <a style="padding: 6px 8px; position: relative;" href="<?= ROOT ?>/views/products/cart.php" class="icon-button">
          <i style="color: #080100;" class="fa-solid fa-cart-shopping fa-lg"></i>
          <div class="count"><?= count($_SESSION['cart'] ?? []) ?> </div>
        </a>
      </li>
      <li>
        <a style="padding: 6px 8px;position: relative;" href="<?= ROOT ?>/views/products/wishlist.php" class="icon-button">
          <i style="color: #080100;" class="fa-solid fa-heart fa-lg"></i>
          <div class="count"><?= count($_SESSION['wishlist'] ?? []) ?> </div>
        </a>
      </li>
      <li>
        <div class="dropdown">
          <?php if (!is_logged_in()) : ?>
            <button class="user-btn icon-button">
              <i style=" color: #080100;" class="fa-solid fa-user fa-xl"></i>
            </button>
          <?php else : ?>
            <button style=" padding: 4px 8px;" class="user-btn icon-button">
              <p class="icon-text"> <?= substr(user('firstname'), 0, 1) . '' . substr(user('lastname'), 0, 1) ?></p>
            </button>
          <?php endif ?>
          <?php if (!is_logged_in()) : ?>
            <div class="dropdown-menu">
              <a href="<?= ROOT ?>/views/auth/login.php" class="login">Login</a>
              <div class="hr"></div>
              <a href="<?= ROOT ?>/views/auth/signup.php" class="signup">Signup</a>
            </div>
          <?php else : ?>
            <div style="left: -200px; right: 0;" class="dropdown-menu">
              <p style="line-height: 0.8;">
                <span style="color: #615959;"><?= user('firstname') . ' ' . user('lastname') ?></span>
                <span><?= user('email') ?></span>
              </p>
              <div class="hr"></div>
              <a href="<?= ROOT ?>/views/user/index.php">Dashboard</a>
              <a style="padding-top: 0px;" href="<?= ROOT ?>/views/user/settings.php">Profile</a>
              <div class="hr"></div>
              <form method="post" action="">
                <button type="submit" name="logout">
                  <p>Log out</p>
                </button>
              </form>
            </div>
          <?php endif ?>
        </div>
      </li>
    </ul>
  </div>
</div>