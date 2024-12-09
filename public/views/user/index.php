<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

$user_id = user('id');
$order_number = getNumberOfOrdersByUser($user_id);
$wishlist_number = count($_SESSION['wishlist'] ?? []);
$cart_number = count($_SESSION['cart'] ?? []);
$categorySpending = getUserSpendingPerCategory($user_id);
$orderStatusCounts = getOrderStatusCounts($user_id);
?>
<main>
  <?php require '../../inc/user_sidebar.php'; ?>
  <div class="user-content">
    <h2>Bonjour, <?= user('firstname') ?></h2>
    <div class="user-dashboard-numbers">
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/order.png" class="icon-dahsboard" />
          <p>Nombre de commandes</p>
        </div>
        <div class="digit"><?= $order_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/user/orders.php">Voir l'historique des commandes</a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/cart.png" class="icon-dahsboard" />
          <p>Nombre de produits dans le panier</p>
        </div>
        <div class="digit"><?= $cart_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/products/cart.php">Voir le panier </a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/wishlist.png" class="icon-dahsboard" />
          <p>Nombre de produits dans la liste de souhaits</p>
        </div>
        <div class="digit"><?= $wishlist_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/products/wishlist.php">Voir la liste de souhaits </a></div>
      </div>
    </div>
    <div class="bar-chart-numbers">
      <div class="bar-chart">
        <h3>Dépenses par catégorie</h3>
        <canvas id="categorySpendingChart"></canvas>
      </div>
      <div class="pie-chart">
        <h3>Statut des commandes</h3>
        <canvas id="orderStatusChart"></canvas>
      </div>
    </div>
  </div>
</main>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('categorySpendingChart').getContext('2d');
    const data = {
      labels: <?= json_encode(array_column($categorySpending, 'category')) ?>,
      datasets: [{
        label: 'Dépenses par catégorie',
        data: <?= json_encode(array_column($categorySpending, 'total_spent')) ?>,
        backgroundColor: [
          'rgba(255, 214, 209, 0.2)',
          'rgba(54, 193, 193, 0.2)',
          'rgba(194, 247, 175, 0.2)',
          'rgba(255, 215, 187, 0.2)',
          'rgba(255, 244, 186, 0.2)',
          'rgba(186, 201, 255, 0.2)'
        ],
        borderColor: [
          'rgba(255, 214, 209, 1)',
          'rgba(54, 193, 193, 1)',
          'rgba(194, 247, 175, 1)',
          'rgba(255, 215, 187, 1)',
          'rgba(255, 244, 186, 1)',
          'rgba(186, 201, 255, 1)'
        ],
        borderWidth: 1
      }]
    };

    const config = {
      type: 'bar',
      data: data,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };

    new Chart(ctx, config);

    const ctx1 = document.getElementById('orderStatusChart').getContext('2d');
    const orderStatusCounts = <?= json_encode($orderStatusCounts); ?>;

    const data1 = {
      labels: Object.keys(orderStatusCounts),
      datasets: [{
        label: 'Statut des Commandes',
        data: Object.values(orderStatusCounts),
        backgroundColor: [
          'rgba(255, 214, 209, 0.8)',
          'rgba(194, 247, 175, 0.8)',
          'rgba(255, 215, 187, 0.8)'
        ],
        hoverOffset: 4
      }]
    };

    const config1 = {
      type: 'doughnut',
      data: data1,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          // title: {
          //   display: true,
          //   text: 'Statut des Commandes'
          // }
        }
      }
    };

    new Chart(ctx1, config1);

  });
</script>
</body>

</html>