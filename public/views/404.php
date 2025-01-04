<?php
require '../inc/navbar.php';
?>
<main class="not-found-container">
  <h1>404 - Page not found</h1>
  <div>
    <img src="<?= ROOT ?>/images/404_error.png" alt="">
  </div>
  <p>Sorry, the page you're looking for does not exist.</p>
  <a href="<?= ROOT ?>/"><button class="secondary-btn">
      <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
      Back to homepage
    </button></a>

</main>
<?php require '../inc/footer.php' ?>