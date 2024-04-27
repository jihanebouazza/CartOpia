<?php
require '../../inc/navbar.php';

// Cart operations
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1; // Default quantity to 1 if not specified

    // Check if the cart session exists
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Add or update the product in the cart
    if (!isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = $quantity;
    }
    // else {
    //   $_SESSION['cart'][$product_id] += $quantity;
    // }
    header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
    exit;
  } elseif (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);  // Remove the item from the cart
    }
  } elseif (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = [];  // Empty the cart
  }
}
// print_r($_SESSION['cart']);
?>

<main class="cart">
  <div class="cart-container">
    <div class="cart-heading">
      <h2>Panier <span>(<?= count($_SESSION['cart'] ?? []) ?> produit<?= count($_SESSION['cart'] ?? []) > 1 ? 's' : '' ?>)</span></h2>
      <form method="post">
        <button name="empty_cart" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i> Vider le panier</button>
      </form>
    </div>
    <?php if (!empty($_SESSION['cart'])) : ?>
      <?php foreach ($_SESSION['cart'] as $product_id => $quantity) : ?>
        <?php
        $product = getProductByID($product_id);
        $basePrice = $product['discount_percentage'] > 0 ? calculateDiscountPrice($product['price'], $product['discount_percentage']) : $product['price'];
        // echo '<pre>';
        // print_r($product);
        // echo '</pre>';
        ?>
        <div class="cart-product">
          <div class="product-img-title">
            <img style="margin-right: 8px;" class="small-img" src="<?= '../' . $product['images'][0] ?>" alt="">
            <a href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="product-title"><?= strlen($product['title']) >= 15 ? substr($product['title'], 0, 15) . '...' : $product['title'] ?></a>
          </div>
          <div class="product-qty">
            <button class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
            <input type="text" value="<?= $quantity ?>" class="qty-input">
            <!-- <input type="text" value="<?= $quantity ?>" class="qty-input" data-price="<?= $unit_price ?>"> -->

            <button style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>
          </div>
          <div class="product-price-cart">
            <p class="product-price">
            <?= $basePrice * $quantity ?>dh
            </p>
          </div>
          <div>
            <form method="post">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <button type="submit" name="remove" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i></button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <div style="height:26vh; width: 100%; display:flex; align-items: center; justify-content: center;">
        <p>Aucun produit trouvé !</p>
      </div>
    <?php endif; ?>
  </div>
  <div class="total-container">
    <h2>Résumé de la commande</h2>
    <div style="margin: 16px 0px 8px;" class="hr"></div>
    <div class="total thin">
      <p>Sous-total</p>
      <p>250 dh</p>
    </div>
    <div style="margin-top: 4px;" class="total thin">
      <p>Livraison</p>
      <p>0 dh</p>
    </div>
    <div style="margin: 8px 0px 8px;" class="hr"></div>
    <div style="font-weight: 700;" class="total">
      <p>Total</p>
      <p>250 dh</p>
    </div>
    <button style="width: 100%;margin-top: 16px;" class="primary-btn">Passer à la caisse</button>
  </div>
</main>
<script src="<?= ROOT ?>/js/quantity.js" defer></script>

<?php require '../../inc/footer.php' ?>