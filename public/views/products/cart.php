<?php
require '../../inc/navbar.php';
$subtotal = 0;

// Cart operations
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    ['stock' => $stock] = getProductByID($product_id);
    $quantity = $_POST['quantity'] ?? 1;

    if ($stock < $quantity) {
      set_message('La quantité demandée dépasse le stock disponible!', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
      exit;
    }
    if ($stock === 0) {
      set_message('Le produit sélectionné n\'est plus en stock !', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }

    // Check if the cart session exists
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    if (!isset($_SESSION['cart_totals'])) {
      $_SESSION['cart_totals'] = ['subtotal' => 0, 'shipping' => 0, 'total' => 0];
    }

    // Add the product to the cart
    if (!isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = $quantity;
      set_message('Le produit sélectionné a été ajouté à votre panier.', 'success');
    } else {
      set_message('Ce produit existe déjà dans votre panier!', 'error');
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
    exit;
  } elseif (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);  // Remove the item from the cart
    }
  } elseif (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = [];  // Empty the cart
    $_SESSION['cart_totals'] = ['subtotal' => 0, 'shipping' => 0, 'total' => 0];
  } else if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    ['stock' => $stock] = getProductByID($product_id);

    if ($new_quantity > $stock) {
      set_message('La quantité demandée dépasse le stock disponible!', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = max(1, $new_quantity);  // Ensure quantity doesn't go below 1
    }
  } elseif (isset($_POST['checkout'])) {
    redirect('views/products/checkout');
  }
}
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
        $subtotal += $basePrice * $quantity;
        $shipping = 0.10 * $subtotal;
        $total = $subtotal + $shipping;
        $_SESSION['cart_totals'] = ['subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $total];
        // print_r($_SESSION['cart_totals']);

        // echo '<pre>';
        // print_r($product);
        // echo '</pre>';
        ?>
        <div class="cart-product">
          <div class="product-img-title">
            <img style="margin-right: 8px;" class="small-img" src="<?= '../' . $product['images'][0] ?>" alt="">
            <a href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="product-title"><?= strlen($product['title']) >= 15 ? substr($product['title'], 0, 15) . '...' : $product['title'] ?></a>
          </div>
          <form method="post" class="product-qty">
            <button type="button" class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
            <input type="text" name="quantity" value="<?= $quantity ?>" class="qty-input" onchange="this.form.submit()">
            <button type="button" style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>

            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <input type="hidden" name="update_quantity" value="1">
            <button type="submit" style="display:none;">Update</button>
          </form>
          <div class="product-price-cart">
            <p class="product-price">
              <?= number_format($basePrice * $quantity, 2) ?>dh
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
        <p>Votre panier est vide !</p>
      </div>
    <?php endif; ?>
  </div>

  <div class="total-container">
    <h2>Résumé de la commande</h2>
    <div style="margin: 16px 0px 8px;" class="hr"></div>
    <div class="total thin">
      <p>Sous-total</p>
      <p><?= !empty($_SESSION['cart_totals']) ? number_format($_SESSION['cart_totals']['subtotal'], 2) : '0.00' ?>dh</p>
    </div>
    <div style="margin-top: 4px;" class="total thin">
      <p>Livraison</p>
      <p><?= !empty($_SESSION['cart_totals']) ? number_format($_SESSION['cart_totals']['shipping'], 2) : '0.00' ?>dh</p>
    </div>
    <div style="margin: 8px 0px 8px;" class="hr"></div>
    <div style="font-weight: 700;" class="total">
      <p>Total</p>
      <p><?= !empty($_SESSION['cart_totals']) ? number_format($_SESSION['cart_totals']['total'], 2) : '0.00' ?>dh</p>
    </div>
    <!-- <button style="width: 100%;margin-top: 16px;" class="primary-btn">Passer à la caisse</button> -->
    <?php if (!empty($_SESSION['cart'])) : ?>
      <form action="" method="post">
        <button style="width: 100%;margin-top: 16px;" type="submit" name="checkout" class="primary-btn">Passer à la caisse</button>
      </form>
    <?php else : ?>
      <button style="width: 100%;margin-top: 16px;" type="button" onclick="alert('Votre panier est vide, ajoutez des produits pour continuer');" class="primary-btn">Passer à la caisse</button>
    <?php endif; ?>
  </div>

</main>
<script src="<?= ROOT ?>/js/cart.js" defer></script>


<?php require '../../inc/footer.php' ?>