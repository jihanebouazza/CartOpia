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
    $errors['order_status'] = 'Veuillez spécifier un statut de commande!';
  }
  if (empty($payment_status)) {
    $errors['payment_status'] = 'Veuillez spécifier un statut de paiement!';
  }

  if (empty($errors)) {
    if (updateOrderStatus($id, $order_status, $payment_status)) {
      set_message("La commande a été modifié avec succès !", "success");
      redirect('views/admin/orders');
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post">
      <h1>Modifier le Statut de la Commande</h1>
      <h2>Mettez à jour le statut de la commande pour refléter les derniers développements.</h2>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div>
        <label class="label">
          Statut de la commande
        </label>
        <select style="width: 100%;" name="order_status" class="input">
          <option value="">Modifier le statut de la commande</option>
          <option value="Traitée" <?= ($order['status'] == "Traitée" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Traitée</option>
          <option value="Expédiée" <?= ($order['status'] == "Expédiée" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Expédiée</option>
          <option value="Livré" <?= ($order['status'] == "Livré" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Livré</option>
          <option value="Annulée" <?= ($order['status'] == "Annulée" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>Annulée</option>
          <option value="En cours de traitement" <?= ($order['status'] == "En cours de traitement" || $order['status'] == post_old_value('order_status')) ? 'selected' : '' ?>>En cours de traitement</option>

        </select>
      </div>
      <?= isset($errors['order_status']) ? '<div class="error">' . $errors['order_status'] . '</div>' : '' ?>
      <div>
        <label class="label">
          Statut de paiement
        </label>
        <select style="width: 100%;" name="payment_status" class="input">
          <option value="">Modifier le statut de paiement</option>
          <option value="Payé" <?= ($order['payment_status'] == "Payé" || $order['payment_status'] == post_old_value('payment_status')) ? 'selected' : '' ?>>Payé</option>
          <option value="En attente" <?= ($order['payment_status'] == "En attente" || $order['payment_status'] == post_old_value('payment_status')) ? 'selected' : '' ?>>En attente</option>
        </select>
      </div>
      <?= isset($errors['payment_status']) ? '<div class="error">' . $errors['payment_status'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Modifier</button>
    </form>
</main>
</body>

</html>