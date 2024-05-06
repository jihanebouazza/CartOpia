<?php
require '../../inc/navbar.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}
// echo '<pre>';
// print_r($_SESSION['USER']);
// echo '</pre>';
$errors = [];
global $con;
$user_id = $_SESSION['USER']['id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $phone_number = htmlspecialchars($_POST['phone_number']);
  $address = htmlspecialchars($_POST['address']);
  $city = htmlspecialchars($_POST['city']);
  $postal_code = htmlspecialchars($_POST['postal_code']);
  $payment_method = htmlspecialchars($_POST['payment_method']);

  if (!preg_match("/^(06|07|\+212)(\s)?[0-9]{8}$/", $phone_number)) {
    $errors['phone_number'] = "Le numéro de téléphone doit commencer par 06, 07 ou +212 suivi de 8 chiffres.";
  }

  if (!preg_match("/^[\w\s.,'éàèùâêîôûäëïöüç-]{10,}$/", $address)) {
    $errors['address'] = "L'adresse est invalide.";
  }

  if (!preg_match("/^[a-zA-Z]{3,}$/", $city)) {
    $errors['city'] = "La ville doit contenir au moins 3 lettres.";
  }

  if (!preg_match("/^[0-9]{5}$/", $postal_code)) {
    $errors['postal_code'] = "Le code postal doit contenir exactement 5 chiffres.";
  }
  if (empty($payment_method)) {
    $errors['payment_method'] = "Veuillez sélectionner une méthode de paiement.";
  }
  if (empty($errors) && !empty($_SESSION['cart']) && !empty($_SESSION['cart_totals']) && $payment_method == 'A la livraison') {
    // if ($payment_method = 'A la livraison') {
    ['total' => $total] = $_SESSION['cart_totals'];

    if (updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)) {
      $order_id = insertOrder($user_id, $total, $payment_method, 'En attente');
      if ($order_id) {
        $all_items_processed = true;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
          $product_price = getProductPrice($product_id);
          insertOrderItem($order_id, $product_id, $quantity, $product_price);
          updateProductStock($product_id, $quantity);
        }
        // if ($all_items_processed) {
        unset($_SESSION['cart']);
        unset($_SESSION['cart_totals']);
        redirect('views/products/success');
        // }
      }
    }
  } elseif (empty($errors) && !empty($_SESSION['cart']) && !empty($_SESSION['cart_totals']) && $payment_method == 'Par carte') {
    if (updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)) {
      redirect('views/products/payment');
    }
    // If cart is empty
  } elseif (empty($_SESSION['cart'])) {
    set_message("Votre panier est vide, ajoutez des produits pour continuer.", "error");
  }
}

// Traitée (Processed): La commande a été confirmée et est en cours de préparation.
// Expédiée (Shipped): Les produits ont été envoyés au client.
// Livré (Delivered): La commande est arrivée à destination.
// Annulée (Cancelled): La commande a été annulée par l'utilisateur ou par le système en raison d'un problème.
?>
<main class="checkout">
  <form method="post" class="checkout-form">
    <h1>Finalisation de votre commande</h1>
    <h2>Veuillez remplir les informations ci-dessous pour compléter votre commande.</h2>
    <div class="double-input">
      <div>
        <div class="flex">
          <label class="label">
            Prénom
          </label>
        </div>
        <p><?= user('firstname') ?></p>
      </div>
      <div>
        <label class="label">
          Nom
        </label>
        <p><?= user('lastname') ?></p>
      </div>
    </div>

    <div>
      <label class="label">
        Email
      </label>
      <p><?= user('email') ?></p>

    </div>
    <div>
      <label class="label">
        Numéro de téléphone
      </label>
      <input value="<?= post_old_value('phone_number') ? post_old_value('phone_number') : user('phone_number') ?>" placeholder="" type="text" name="phone_number" class="input">
    </div>
    <?= isset($errors['phone_number']) ? '<div class="error">' . $errors['phone_number'] . '</div>' : '' ?>

    <div>
      <label class="label">
        Adresse
      </label>
      <textarea placeholder="" name="address" class="input"><?= post_old_value('address') ? post_old_value('address') : user('address')   ?></textarea>
    </div>
    <?= isset($errors['address']) ? '<div class="error">' . $errors['address'] . '</div>' : '' ?>

    <div class="double-input">
      <div>
        <div class="flex">
          <label class="label">
            Ville
          </label>
        </div>
        <input value="<?= post_old_value('city') ? post_old_value('city') : user('city')   ?>" placeholder="" type="text" name="city" class="input">
      </div>
      <div>
        <label class="label">
          Code Postal
        </label>
        <input value="<?= post_old_value('postal_code') ? post_old_value('postal_code') : user('postal_code') ?>" placeholder="" type="text" name="postal_code" class="input">
      </div>
    </div>
    <?= isset($errors['city']) ? '<div class="error">' . $errors['city'] . '</div>' : '' ?>
    <?= isset($errors['postal_code']) ? '<div style="margin-top: 4px" class="error">' . $errors['postal_code'] . '</div>' : '' ?>
    <div>
      <label class="label">
        Méthode de Paiement
      </label>
      <select style="width: 100%;" name="payment_method" class="input">
        <option value="">Sélectionnez la méthode de paiement</option>
        <option value="Par carte"> Carte de crédit</option>
        <option value="A la livraison">Paiement à la livraison</option>
      </select>
    </div>
    <?= isset($errors['payment_method']) ? '<div class="error">' . $errors['payment_method'] . '</div>' : '' ?>

    <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Confirmer la commande</button>

  </form>
</main>
<?php require '../../inc/footer.php' ?>