<?php
ob_start();
require '../../inc/navbar.php';

// Initialize the session for wishlist if it doesn't exist
if (!isset($_SESSION['wishlist'])) {
  $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $product_id = $_POST['product_id'] ?? null;  // Get product ID from form submission

  if (isset($_POST['add-to-wishlist'])) {
    // Add product to wishlist
    if (!in_array($product_id, $_SESSION['wishlist'])) {
      $_SESSION['wishlist'][] = $product_id;
    }
  } elseif (isset($_POST['remove-from-wishlist'])) {
    // Remove product from wishlist
    if (($key = array_search($product_id, $_SESSION['wishlist'])) !== false) {
      unset($_SESSION['wishlist'][$key]);
    }
  } elseif (isset($_POST['empty_wishlist']) && !empty($_SESSION['wishlist'])) {
    // Empty the wishlist
    $_SESSION['wishlist'] = [];
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']); 
  exit;
}
?>


<main class="wishlist-container">
  <div class="cart-heading">
    <h2>Wishlist <span class="number">(<?= count($_SESSION['wishlist'] ?? []) ?> product<?= count($_SESSION['wishlist'] ?? []) > 1 ? 's' : '' ?>)</span></h2>
    <form method="post">
      <button name="empty_wishlist" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i> Empty the wishlist </button>
    </form>
  </div>
  <?php if (!empty($_SESSION['wishlist'])) : ?>
    <?php foreach ($_SESSION['wishlist'] as $product_id) : ?>
      <?php
      $product = getProductByID($product_id);
      $basePrice = $product['discount_percentage'] > 0 ? calculateDiscountPrice($product['price'], $product['discount_percentage']) : $product['price'];
      ?>
      <div class="cart-product">
        <div class="product-img-title">
          <img style="margin-right: 8px;" class="small-img" src="<?= '../' . $product['images'][0] ?>" alt="">
          <a href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="product-title"><?= strlen($product['title']) >= 15 ? substr($product['title'], 0, 15) . '...' : $product['title'] ?></a>
        </div>
        <div class="product-price-cart">
          <p class="product-price">
            <?= number_format($basePrice, 2) ?>dh
          </p>
        </div>
        <form style="display: inline-block;" method="post" action="cart.php">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button name="add" type="submit" class="secondary-btn-small"><i style="color: #ff988d;" class="fa-solid fa-cart-shopping fa-lg"></i> Add to cart</button>
        </form>
        <div>
          <form method="post">
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <button type="submit" name="remove-from-wishlist" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i></button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <div style="height:26vh; width: 100%; display:flex; align-items: center; justify-content: center;">
      <p>Your wishlist is empty!</p>
    </div>
  <?php endif; ?>
</main>
</body>

</html>

<?php ob_end_flush();  