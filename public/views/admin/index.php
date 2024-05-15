<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$orders_number = count(getAllOrders() ?? []);
$users_number = count(getAllUsers() ?? []);
$products_number = count(getAllProducts() ?? []);
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content">
    <h2>Bonjour, <?= user('firstname') ?></h2>
    <div class="user-dashboard-numbers">
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/order.png" class="icon-dahsboard" />
          <p>Nombre de commandes</p>
        </div>
        <div class="digit"><?= $orders_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/orders.php">Voir tous les commandes</a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/cart.png" class="icon-dahsboard" />
          <p>Nombre de produits</p>
        </div>
        <div class="digit"><?= $products_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/products.php">Voir tous les produits </a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <img src="<?= ROOT ?>/images/users.png" class="icon-dahsboard" />
          <p>Nombre des utilsateurs</p>
        </div>
        <div class="digit"><?= $users_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/users.php">Voir tous les utilisateurs</a></div>
      </div>
    </div>
    <div class="bar-chart-numbers">
      <div class="bar-chart">
        <canvas id="productStockLevelsChart"></canvas>
      </div>
      <div class="pie-chart">
        <canvas id="categoryDistributionChart"></canvas>
      </div>
    </div>
  </div>
</main>
</body>

</html>

<script>
  //  Each bar represents a category, and its height represents the total stock level of products 
  // in that category.
  var productStockLevelsData = <?php echo json_encode(getProductStockLevelsData()); ?>;
  var productStockLevelsCanvas = document.getElementById('productStockLevelsChart').getContext('2d');
  var productStockLevelsChart = new Chart(productStockLevelsCanvas, {
    type: 'bar',
    data: {
      labels: Object.keys(productStockLevelsData),
      datasets: [{
        label: 'Niveaux de stock des produits',
        data: Object.values(productStockLevelsData),
        backgroundColor: 'rgba(255, 214, 209, 0.2)',
        borderColor: 'rgba(255, 214, 209, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      },
      responsive: true
    }
  });

  //  the distribution of products across different categories. Each slice of the pie represents a category,
  //  and its size corresponds to the proportion of products in that category.
  var categoryDistributionData = <?php echo json_encode(getCategoryDistributionData()); ?>;
  var categoryDistributionCanvas = document.getElementById('categoryDistributionChart').getContext('2d');
  var categoryDistributionChart = new Chart(categoryDistributionCanvas, {
    type: 'pie',
    data: {
      labels: Object.keys(categoryDistributionData),
      datasets: [{
        data: Object.values(categoryDistributionData),
        backgroundColor: [
          'rgba(255, 214, 209, 0.2)',
          'rgba(194, 247, 175, 0.2)',
          'rgba(255, 215, 187, 0.2)',
          'rgba(255, 244, 186, 0.2)',
          'rgba(186, 201, 255, 0.2)',
          'rgba(54, 193, 193, 0.2)',
        ],
        borderColor: [
          'rgba(255, 214, 209, 1)',
          'rgba(194, 247, 175, 1)',
          'rgba(255, 215, 187, 1)',
          'rgba(255, 244, 186, 1)',
          'rgba(186, 201, 255, 1)',
          'rgba(54, 193, 193, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
</script>