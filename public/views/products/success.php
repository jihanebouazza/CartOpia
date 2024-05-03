<?php
require '../../inc/navbar.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

if (empty($_SERVER['HTTP_REFERER'])) {
  $user_id = $_SESSION['USER']['id'];
  if (!empty($_SESSION['cart']) && !empty($_SESSION['cart_totals'])) {
    ['total' => $total] = $_SESSION['cart_totals'];

    $order_id = insertOrder($user_id, $total, 'Par carte', 'Payé');
    if ($order_id) {
      foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_price = getProductPrice($product_id);
        insertOrderItem($order_id, $product_id, $quantity, $product_price);
        updateProductStock($product_id, $quantity);
      }
      unset($_SESSION['cart']);
      unset($_SESSION['cart_totals']);
    }
  }
}

?>
<main class="success-container">
  <div>
    <h1>Commande Confirmée</h1>
    <h2>Votre commande a été traitée avec succès. Merci de votre achat !</h2>
    <p>Pour consulter les détails de cette commande ou suivre son statut, veuillez visiter votre <a style="text-decoration: underline; color:#EE786B" href="<?= ROOT ?>/views/user/order.php">historique de commandes</a>.</p>
  </div>
  <dotlottie-player src="https://lottie.host/99290e75-e0b3-47b3-972a-55701e24e5c6/1xvRbthtb7.json" background="transparent" speed="1" style="width:250px; height:250px" loop autoplay></dotlottie-player>
</main>

<?php
require '../../inc/footer.php';
?>