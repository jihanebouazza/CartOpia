<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<style>
  body {
    font-family: sans-serif;
  }

  p {
    font-size: 16px;
    margin: 0;
  }

  .table {
    font-size: 16px;
    width: 100%;
  }

  .table thead th{
   color: #4D4444;
  }
  .table thead th,
  .table tbody td {
    padding: 6px 4px;
  }

  .table th,
  .table td {
    border-bottom: 1px solid #bab3b2;
    text-align: center;

  }
</style>

<body>
  <header>
    <h1>Facture</h1>
    <div style="margin-bottom: 24px;">
      <p><span style="font-weight: bold;color: #011351;">N° de la commande:</span> <?= $order['order_id'] ?></p>
      <p><span style="font-weight: bold;color: #011351;">Date de la commande:</span> <?= explode(' ', $order['date'])[0] ?></p>
      <p><span style="font-weight: bold;color: #011351;">Status de commande:</span> <?= $order['status'] ?></p>
      <p><span style="font-weight: bold;color: #011351;">Type de paiment:</span> <?= $order['payment_type'] ?></p>
      <p><span style="font-weight: bold;color: #011351;">Status de paiment:</span> <?= $order['payment_status'] ?></p>
    </div>

  </header>
  <h2>Détails de la commande:</h2>
  <table class="table">
    <thead>
      <th>Nom du produit</th>
      <th>Quantité</th>
      <th>Prix unitaire</th>
      <th>Montant total</th>
    </thead>
    <tbody>
      <?php foreach ($order['order_items'] as $order_item) : ?>
        <tr>
          <!-- <td><?= strlen($order_item['product']['title']) >= 25 ? substr($order_item['product']['title'], 0, 25) . '...' : $order_item['product']['title'] ?></td> -->
          <td><?= $order_item['product']['title'] ?></td>
          <td><?= $order_item['quantity'] ?></td>
          <td><?= number_format($order_item['price'], 2) ?></td>
          <td><?= number_format($order_item['price'] * $order_item['quantity'], 2) ?></?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div style="margin-top: 16px;">
    <p style="font-size: 18px;"><span style="font-weight: bold;color: #4D4444;">Sous-total:</span> <?= number_format($order['total'] / 1.10, 2) ?>
    <p style="font-size: 18px;"><span style="font-weight: bold;color: #4D4444;">Livraison:</span> <?= number_format(($order['total'] / 1.10) * 0.10, 2) ?>
    <p style="font-size: 18px;"><span style="font-weight: bold;color: #4D4444;">Total:</span> <?= number_format($order['total'], 2) ?>
  </div>
  <footer style="margin-top: 32px; text-align: center; font-weight: bold;">
    <h2>Merci de votre achat!</h2>
  </footer>
</body>

</html>