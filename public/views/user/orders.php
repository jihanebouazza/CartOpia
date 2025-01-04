<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

$user_id = user("id");
$user_orders = getAllOrdersByUserId($user_id);
?>
<main>
  <?php require '../../inc/user_sidebar.php'; ?>
  <div class="user-content">
    <h2>Order history</h2>
    <?php if (!empty($user_orders)) : ?>
      <table style="margin-top: 16px;" class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Status</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($user_orders as $order) : ?>
            <tr>
              <td><?= $order['id'] ?></td>
              <td><?= $order['status'] ?></td>
              <td><?= explode(' ', $order['date'])[0] ?></td>
              <td><?= number_format($order['total'], 2) ?></td>
              <td><?= $order['payment_type'] ?></td>
              <td>
                <a target="_blank" href="<?= ROOT ?>/views/user/pdf.php?id=<?= $order['id'] ?>">
                  <button class="secondary-btn-small"><i style="color: #ff988d;" class="fa-solid fa-print fa-lg"></i> Print</button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <div style="height:26vh; width: 100%; display:flex; align-items: center; justify-content: center;">
        <p>You don't have any orders yet.</p>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>

</html>