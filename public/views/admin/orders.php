<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

$orders = getAllOrders();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id  = htmlspecialchars($_POST['id']);

  if (deleteOrderByID($id)) {
    set_message('Order successfully deleted!', "success");
    header('Location:' . $_SERVER['HTTP_REFERER']);
  }
}

?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>
  <div class="user-content">
    <h2>All Orders</h2>
    <?php if (!empty($orders)) : ?>
      <table style="margin-top: 16px;" class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Status</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order) : ?>
            <tr>
              <td><?= $order['id'] ?></td>
              <td><?= $order['status'] ?></td>
              <td><?= explode(' ', $order['date'])[0] ?></td>
              <td><?= number_format($order['total'], 2) ?></td>
              <td><?= $order['payment_type'] ?></td>
              <td><?= $order['payment_status'] ?></td>
              <td>
                <a target="_blank" href="<?= ROOT ?>/views/user/pdf.php?id=<?= $order['id'] ?>" class="icon-button" style="color: #A19796; border-color: #A19796;padding: 4px 4px 4px 6px;">
                  <i style="color: #A19796;" class="fa-solid fa-print fa-sm"></i>
                </a>
                <a href="<?= ROOT ?>/views/admin/order_edit.php?id=<?= $order['id'] ?>" style="margin: 0px 4px; color: #A19796; border-color: #A19796;padding: 4px 4px 4px 6px;" href="<?= ROOT ?>/views/products/cart.php" class="icon-button">
                  <i style="color: #A19796;" class="fa-solid fa-pen-to-square fa-sm"></i>
                </a>
                <form method="post" style="display: inline-block;" action="">
                  <input type="hidden" name="id" value="<?= $order['id'] ?>">
                  <button style="cursor:pointer; background: none; border:solid 1px #f56262;padding: 6px 10px;" type="submit" class="icon-button">
                    <i style="color: #f56262;" class="fa-solid fa-trash fa-sm"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <div style="height:26vh; width: 100%; display:flex; align-items: center; justify-content: center;">
        <p>No orders found!</p>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>

</html>