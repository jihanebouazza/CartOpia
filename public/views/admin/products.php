<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$products = getAllProductsAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = htmlspecialchars($_POST['id']);
  if (deleteProduct($id)) {
    $images =  fetchProductImages($id);
    foreach ($images as $image) {
      unlink('../' . $image);
    }
    set_message('Produit supprimmer avec succès!', 'success');
    header('Location:' . $_SERVER['HTTP_REFERER']);
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content">
    <div class="flex">
      <h2>Tous les produits</h2>
      <a href="<?= ROOT ?>/views/admin/product_add.php"><button class="primary-btn-small "><i style="color: #fffdfd;" class="fa-solid fa-circle-plus fa-sm"></i> Ajouter un produit</button></a>
    </div>
    <?php if (!empty($products)) : ?>
      <table style="margin-top: 16px;" class="table">
        <thead>
          <tr>
            <th>Num</th>
            <th>Image</th>
            <th>Titre</th>
            <th>Categorie</th>
            <th>Stock</th>
            <th>Prix</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) : ?>
            <tr>
              <td><?= $product['id'] ?></td>
              <td><img style="height: 40px; width: 40px; border-radius: 8px; object-fit: cover;" src="<?= '../' . $product['images'][0] ?>" alt=""></td>
              <td><?= strlen($product['title']) >= 20 ? substr($product['title'], 0, 20) . '...' :  $product['title']  ?></td>
              <td><?= $product['category_title'] ?></td>
              <td><?= $product['stock'] ?></td>
              <td><?= $product['discount_percentage'] > 0 ? number_format(calculateDiscountPrice($product['price'], $product['discount_percentage']), 2) : number_format($product['price'], 2) ?></td>
              <td>
                <a style="color: #A19796; border-color: #A19796; padding: 4px 4px 4px 6px;" href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="icon-button">
                  <i style="color: #A19796;" class="fa-solid fa-eye fa-sm"></i>
                </a>
                <a href="<?= ROOT ?>/views/admin/product_edit.php?id=<?= $product['id'] ?>" style="margin: 0px 4px; color: #A19796; border-color: #A19796;padding: 4px 4px 4px 6px;" href="<?= ROOT ?>/views/products/cart.php" class="icon-button">
                  <i style="color: #A19796;" class="fa-solid fa-pen-to-square fa-sm"></i>
                </a>
                <form method="post" style="display: inline-block;" action="">
                  <input type="hidden" name="id" value="<?= $product['id'] ?>">
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
        <p>Aucun produit trouvé !</p>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>

</html>