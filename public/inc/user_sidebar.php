<?php
if (isset($_POST['logout'])) {
  if (!empty($_SESSION['USER'])) {
    unset($_SESSION['USER']);
    set_message('Vous avez été déconnecté avec succès.', 'success');
    redirect('index');
  }
}
?>

<div class="user-sidebar">
  <?php require '../../inc/logo.php'; ?>
  <div class="sidebar-row">
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/user/index.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/user/"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/user/orders.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/user/orders.php"><i class="fa-solid fa-basket-shopping"></i> Historique des commandes</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/user/settings.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/user/settings.php"><i class="fa-solid fa-gears"></i> Paramètres du compte</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/user/change_password.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/user/change_password.php"><i class="fa-solid fa-lock"></i> Mot de passe</a>
    <form method="post" action="">
      <button class="dashboard-link" type="submit" name="logout">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</div>