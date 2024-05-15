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
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/admin/index.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/admin/"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/admin/products.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/admin/products.php"><i class="fa-solid fa-cart-shopping"></i> Gestion des Produits</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/admin/categories.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/admin/categories.php"><i class="fa-solid fa-layer-group"></i>  Gestion des Catégories</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/admin/orders.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/admin/orders.php"><i class="fa-solid fa-basket-shopping"></i> Gestion des Commandes</a>
    <a class="dashboard-link <?= $_SERVER['PHP_SELF'] === '/cartopia/public/views/admin/users.php' ? 'active-dashboard-link' : '' ?>" href="<?= ROOT ?>/views/admin/users.php"><i class="fa-solid fa-user"></i>  Gestion des Utilisateurs</a>
    <form method="post" action="">
      <button class="dashboard-link" type="submit" name="logout">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</div>