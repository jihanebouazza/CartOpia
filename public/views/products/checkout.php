<?php
ob_start();
require '../../inc/navbar.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}
$errors = [];
$user_id = $_SESSION['USER']['id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $phone_number = htmlspecialchars($_POST['phone_number']);
  $address = htmlspecialchars($_POST['address']);
  $city = htmlspecialchars($_POST['city']);
  $postal_code = htmlspecialchars($_POST['postal_code']);
  $payment_method = htmlspecialchars($_POST['payment_method']);

  if (!preg_match("/^(06|07|\+212)(\s)?[0-9]{8}$/", $phone_number)) {
    $errors['phone_number'] = "The phone number must start with 06, 07, or +212 followed by 8 digits.";
  }

  if (!preg_match("/^[\w\s.,'éàèùâêîôûäëïöüç-]{10,}$/", $address)) {
    $errors['address'] = "The address is invalid!";
  }

  if (!preg_match("/^[a-zA-Z]{3,}$/", $city)) {
    $errors['city'] = "The city must contain at least 3 letters!";
  }

  if (!preg_match("/^[0-9]{5}$/", $postal_code)) {
    $errors['postal_code'] = "The postal code must contain exactly 5 digits.";
  }
  if (empty($payment_method)) {
    $errors['payment_method'] = "Please select a payment method.";
  }
  if (empty($errors) && !empty($_SESSION['cart']) && !empty($_SESSION['cart_totals'])) {
    if ($payment_method == 'Cash on delivery') {
      ['total' => $total] = $_SESSION['cart_totals'];

      if (updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)) {
        $order_id = insertOrder($user_id, $total, $payment_method, 'Pending');
        if ($order_id) {
          foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_price = getProductPrice($product_id);
            insertOrderItem($order_id, $product_id, $quantity, $product_price);
            updateProductStock($product_id, $quantity);
          }
          unset($_SESSION['cart']);
          unset($_SESSION['cart_totals']);
          redirect('views/products/success');
        }
      }
    } elseif ($payment_method == 'By Card') {
      if (updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)) {
        redirect('views/products/payment');
      }
    }
  } elseif (empty($_SESSION['cart'])) {
    set_message("Your cart is empty, add products to continue.", "error");
  }
}
?>
<main class="checkout">
  <form method="post" class="checkout-form">
    <h1>Complete your order</h1>
    <h2>Please fill in the information below to complete your order.</h2>
    <div class="double-input">
      <div>
        <div class="flex">
          <label class="label">
            First name
          </label>
        </div>
        <p><?= user('firstname') ?></p>
      </div>
      <div>
        <label class="label">
          Last name
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
        Phone number
      </label>
      <input value="<?= post_old_value('phone_number') ? post_old_value('phone_number') : user('phone_number') ?>" placeholder="" type="text" name="phone_number" class="input">
    </div>
    <?= isset($errors['phone_number']) ? '<div class="error">' . $errors['phone_number'] . '</div>' : '' ?>

    <div>
      <label class="label">
        Address
      </label>
      <textarea placeholder="" name="address" class="input"><?= post_old_value('address') ? post_old_value('address') : user('address')   ?></textarea>
    </div>
    <?= isset($errors['address']) ? '<div class="error">' . $errors['address'] . '</div>' : '' ?>

    <div class="double-input">
      <div>
        <div class="flex">
          <label class="label">
            City
          </label>
        </div>
        <input value="<?= post_old_value('city') ? post_old_value('city') : user('city')   ?>" placeholder="" type="text" name="city" class="input">
      </div>
      <div>
        <label class="label">
          Postal code
        </label>
        <input value="<?= post_old_value('postal_code') ? post_old_value('postal_code') : user('postal_code') ?>" placeholder="" type="text" name="postal_code" class="input">
      </div>
    </div>
    <?= isset($errors['city']) ? '<div class="error">' . $errors['city'] . '</div>' : '' ?>
    <?= isset($errors['postal_code']) ? '<div style="margin-top: 4px" class="error">' . $errors['postal_code'] . '</div>' : '' ?>
    <div>
      <label class="label">
        Payment method
      </label>
      <select style="width: 100%;" name="payment_method" class="input">
        <option value="">Select the payment method</option>
        <option value="By Card">Payment by card</option>
        <option value="Cash on delivery">Cash on delivery</option>
      </select>
    </div>
    <?= isset($errors['payment_method']) ? '<div class="error">' . $errors['payment_method'] . '</div>' : '' ?>

    <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Confirm order</button>

  </form>
</main>
<?php require '../../inc/footer.php' ?>
<?php ob_end_flush();