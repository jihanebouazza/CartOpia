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
    <h2>Hello, <?= user('firstname') ?></h2>
    <div class="user-dashboard-numbers">
      <div class="number">
        <div class="number-div">
          <svg class="icon-dahsboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><defs><style>.cls-1{fill:#dad7e5}.cls-4{fill:#919191}</style></defs><g id="Shopping_List" data-name="Shopping List"><path class="cls-1" d="M36 23v19a5 5 0 0 1-10 0v-2H8V10h21.81l-.54 8.75a4 4 0 0 0 4 4.25z"/><path d="M36 23v19a5 5 0 0 1-5 5H14V10h15.81l-.54 8.75a4 4 0 0 0 4 4.25z" style="fill:#edebf2"/><path d="M31 47H7a6 6 0 0 1-6-6v-1h25c0 2.15-.22 3.84 1.46 5.54A5 5 0 0 0 31 47z" style="fill:#c6c3d8"/><path class="cls-1" d="M28 46H18a4 4 0 0 1-4-4v-2h12c0 2.28-.24 4.35 2 6z"/><path class="cls-4" d="M13 23a1 1 0 0 0-2 0 1 1 0 0 0 2 0M13 28a1 1 0 0 0-2 0 1 1 0 0 0 2 0M26 24H16a1 1 0 0 1 0-2h10a1 1 0 0 1 0 2zM13 18a1 1 0 0 0-2 0 1 1 0 0 0 2 0M26 19H16a1 1 0 0 1 0-2h10a1 1 0 0 1 0 2zM32 29H16a1 1 0 0 1 0-2h16a1 1 0 0 1 0 2zM13 33a1 1 0 0 0-2 0 1 1 0 0 0 2 0M32 34H16a1 1 0 0 1 0-2h16a1 1 0 0 1 0 2z"/><path d="M46.73 18.75a4 4 0 0 1-4 4.25h-9.47a4 4 0 0 1-4-4.25c.62-10 .51-8.33.61-9.87a2 2 0 0 1 2-1.88h12.25a2 2 0 0 1 2 1.88z" style="fill:#fc6"/><path class="cls-1" d="M36 23v1h-3.74a4 4 0 0 1-4-4.25c.61-9.84.51-8.33.6-9.75h.94l-.54 8.75a4 4 0 0 0 4 4.25z"/><path d="M46.2 21H38a4 4 0 0 1-4-4V7h10.12a2 2 0 0 1 2 1.88C46.75 19 47 19.58 46.2 21z" style="fill:#ffde76"/><path d="M41 9V6a3 3 0 0 0-6 0v3a1 1 0 0 1-2 0V6a5 5 0 0 1 10 0v3a1 1 0 0 1-2 0z" style="fill:#a87e6b"/></g></svg>
          <p>Number of Orders</p>
        </div>
        <div class="digit"><?= $orders_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/orders.php">View All Orders</a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <svg class="icon-dahsboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><defs><style>.cls-1{fill:#7c7d7d}.cls-2{fill:#919191}.cls-3{fill:#edb996}.cls-4{fill:#f6ccaf}.cls-5{fill:#edebf2}</style></defs><g id="Stock_product" data-name="Stock product"><path class="cls-1" d="M5 3v44H1V3a2 2 0 0 1 4 0z"/><path class="cls-2" d="M5 3c0 43.26-.1 42 0 42a2 2 0 0 1-2-2V1a2 2 0 0 1 2 2z"/><path class="cls-1" d="M47 3v44h-4V3a2 2 0 0 1 4 0z"/><path class="cls-2" d="M47 3v42h-4V3a2 2 0 0 1 4 0z"/><path class="cls-1" d="M5 40h38v4H5z"/><path class="cls-2" d="M5 40h36v4H5z"/><path class="cls-1" d="M5 17h38v4H5z"/><path class="cls-2" d="M5 17h36v4H5z"/><path class="cls-3" d="M5 26h14v14H5z"/><path class="cls-4" d="M19 26c0 12.64-.1 12 0 12A12 12 0 0 1 7 26z"/><path class="cls-3" d="M5 5h20v12H5z"/><path class="cls-4" d="M25 5v10h-8A10 10 0 0 1 7 5z"/><path class="cls-3" d="M31 28h12v12H31z"/><path class="cls-4" d="M41 28c0 10.61-.1 10 0 10a8 8 0 0 1-8-8v-2z"/><path class="cls-3" d="M19 30h12v10H19z"/><path class="cls-4" d="M31 30v8h-2a8 8 0 0 1-8-8z"/><path class="cls-3" d="M25 7h18v10H25z"/><path class="cls-4" d="M41 7v8h-6a8 8 0 0 1-8-8z"/><path class="cls-5" d="M13 5h4v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5zM32 7h4v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V7zM10 26h4v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zM23 30h4v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zM35 28h4v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3z"/></g></svg>
          <p>Number of Products</p>
        </div>
        <div class="digit"><?= $products_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/products.php">View All Products</a></div>
      </div>
      <div class="number">
        <div class="number-div">
          <svg class="icon-dahsboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><g data-name="Delivery Man"><path d="M58 36v14.73a4 4 0 0 0-.32-.4 9.4 9.4 0 0 0-1.93-1.79C54.58 47.678 53.09 46.528 37 40v-5h20a1 1 0 0 1 1 1zM6 36v14.73a4 4 0 0 1 .32-.4 9.4 9.4 0 0 1 1.93-1.79C9.42 47.678 10.91 46.528 27 40v-5H7a1 1 0 0 0-1 1z" style="fill:#ea972a"/><path d="M44.643 21H41v-2a25.086 25.086 0 0 1-13.93-4.93l-.03.09A4.924 4.924 0 0 1 23 17.35V21h-3a2 2 0 0 0-1 .27V10a5 5 0 0 1 5-5 3.995 3.995 0 0 1 4-4h5.76a14.393 14.393 0 0 1 9.11 3.15 9.932 9.932 0 0 1 4.12 8.31A19.32 19.32 0 0 1 44.643 21z" style="fill:#494a59"/><path d="M53.79 44.46c-3.8-1.558-11.7-4.8-15.79-6.46l-6 5-6-5c-2.241.908-3.718 1.515-15.79 6.46A10 10 0 0 0 4 53.71v8.54a.75.75 0 0 0 .75.75h54.5a.75.75 0 0 0 .75-.75v-8.54a10 10 0 0 0-6.21-9.25z" style="fill:#e6ecff"/><path d="M38 31.7V39l-6 5-6-5v-7.3a8.976 8.976 0 0 0 12 0zM46 22v3a2.006 2.006 0 0 1-2 2h-4.06a8.262 8.262 0 0 0 .06-1v-6h4a2.006 2.006 0 0 1 2 2zM24.06 27H20a2.006 2.006 0 0 1-2-2v-3a2.006 2.006 0 0 1 2-2h4v6a8.262 8.262 0 0 0 .06 1z" style="fill:#eac2b9"/><path style="fill:#bbcaea" d="M31.25 42.375V63h1.5V42.375L32 43l-.75-.625z"/><path d="M53 63V33a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v30" style="fill:#ffb64d"/><path d="M53 33v2h-6v-2a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1zM11 63V33a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v30" style="fill:#ffb64d"/><circle cx="35" cy="53" r="1" style="fill:#d0dbf7"/><circle cx="35" cy="49" r="1" style="fill:#d0dbf7"/><circle cx="35" cy="57" r="1" style="fill:#d0dbf7"/><circle cx="35" cy="61" r="1" style="fill:#d0dbf7"/><path style="fill:#ea972a" d="M47 49h6v5h-6z"/><path transform="rotate(-180 14 51.5)" style="fill:#ea972a" d="M11 49h6v5h-6z"/><path d="M44.39 40.61 38 47l-6-4 6-5c1.98.81 4.22 1.73 6.39 2.61zM32 43l-6 4-6.39-6.39C21.83 39.7 24.1 38.77 26 38z" style="fill:#d0dbf7"/><path d="M41 18a25.056 25.056 0 0 1-13.925-4.928l-.034.093A4.905 4.905 0 0 1 23 16.347V26a9 9 0 0 0 9 9 9 9 0 0 0 9-9z" style="fill:#ffddd4"/></g></svg>
          <p>Number of Users</p>
        </div>
        <div class="digit"><?= $users_number ?></div>
        <div class="digit"><a href="<?= ROOT ?>/views/admin/users.php">View All Users</a></div>
      </div>
    </div>
    <div class="bar-chart-numbers">
      <div class="bar-chart">
        <h3>Stock by Category</h3>
        <canvas id="productStockLevelsChart"></canvas>
      </div>
      <div class="pie-chart">
        <h3>Number of Products by Category</h3>
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
        label: 'Product stock levels',
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