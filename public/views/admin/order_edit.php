<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$id = $_GET['id'] ?? 0;
$order = getOrderByID($id);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $id = htmlspecialchars($_POST['id']);
  $order_status = htmlspecialchars($_POST['order_status']);
  $payment_status = htmlspecialchars($_POST['payment_status']);

  if (empty($order_status)) {
    $errors['order_status'] = 'Please specify an order status!';
  }
  if (empty($payment_status)) {
    $errors['payment_status'] = 'Please specify a payment status!';
  }

  if (empty($errors)) {
    if (updateOrderStatus($id, $order_status, $payment_status)) {
      set_message("The order has been successfully updated!", "success");
      redirect('views/admin/orders');
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post">
      <h1>Update Order Status</h1>
      <h2>Update the order status to reflect the latest developments.</h2>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div>
        <label class="label">
        Order Status
        </label>
        <select style="width: 100%;" name="order_status" class="input">
          <option value="">Update order status</option>
          <option value="Processed" <?= ($order['status'] == "Processed" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Processed</option>
          <option value="Shipped" <?= ($order['status'] == "Shipped" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Shipped</option>
          <option value="Delivered" <?= ($order['status'] == "Delivered" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Delivered</option>
          <option value="Canceled" <?= ($order['status'] == "Canceled" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Canceled</option>
          <option value="In Progress" <?= ($order['status'] == "In Progress" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>In Progress</option>

        </select>
      </div>
      <?= isset($errors['order_status']) ? '<div class="error">' . $errors['order_status'] . '</div>' : '' ?>
      <div>
        <label class="label">
          Payment status
        </label>
        <select style="width: 100%;" name="payment_status" class="input">
          <option value="">Update payment status</option>
          <option value="Payed" <?= ($order['payment_status'] == "Payed" || $order['payment_status'] == post_old_value('payment_status')) ? 'selected' : '' ?>>Payed</option>
          <option value="Pending" <?= ($order['payment_status'] == "Pending" || $order['payment_status'] == post_old_value('payment_status')) ? 'selected' : '' ?>>Pending</option>
        </select>
      </div>
      <?= isset($errors['payment_status']) ? '<div class="error">' . $errors['payment_status'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Edit</button>
    </form>
</main>
</body>

</html>