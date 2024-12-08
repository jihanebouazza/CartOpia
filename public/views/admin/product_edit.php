<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$id = $_GET['id'] ?? 0;
$product = getProductByID($id);

$categories = getAllCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $id = htmlspecialchars($_POST['id']);
  $title = htmlspecialchars($_POST['title']);
  $description = htmlspecialchars($_POST['description']);
  $category = htmlspecialchars($_POST['category']);
  $brand = htmlspecialchars($_POST['brand']);
  $stock = htmlspecialchars($_POST['stock']);
  $price = htmlspecialchars($_POST['price']);
  $discount_percentage = htmlspecialchars($_POST['discount_percentage']);
  $old_images = json_decode($_POST['old_images'], true);
  $images = $_FILES['images'];

  $folder = "../../src/uploads/";
  $allowed_ext = ['image/jpeg', 'image/png', 'image/webp'];

  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $title) && !strlen($title) >= 3) {
    $errors['title'] = 'Le titre est Invalide!';
  }
  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $description) && !strlen($description) >= 10) {
    $errors['description'] = 'La description est Invalide!';
  }
  if (empty($category)) {
    $errors['category'] = 'La catégorie est obligatoire!';
  }
  if (!preg_match("/^[\w\s.,'éàèùâêîôûäëïöüç-]{3,}$/", $brand)) {
    $errors['brand'] = 'La marque est Invalide!';
  }
  if ($stock <= 0) {
    $errors['stock'] = 'Le stock est Invalide!';
  }
  if ($price <= 0) {
    $errors['price'] = 'Le prix est Invalide!';
  }

  if ($discount_percentage < 0 || $discount_percentage > 90) {
    $errors['discount_percentage'] = 'Le pourcentage de réduction est Invalide!';
  }
  if (!empty($images['name'][0])) {
    foreach ($images['type'] as $image) {
      if (!in_array($image, $allowed_ext)) {
        $errors['images'] = 'Le type d\'image est Invalide!';
      }
    }
  }

  if (empty($errors)) {
    if (updateProduct($id, $title, $description, $category, $brand, $stock, $price, $discount_percentage)) {
      set_message("Le produit a été modifié avec succès !", "success");
    } else {
      set_message("Échec de la mise à jour du produit.", "error");
    }

    if (!empty($images['name'][0])) {
      // Remove the old images from the upload folder
      foreach ($old_images as $old_image) {
        unlink('../' . $old_image);
      }

      // Upload new images
      for ($i = 0; $i < count($images['tmp_name']); $i++) {
        $tmp_name = $images['tmp_name'][$i];
        $filename = $images['name'][$i];
        move_uploaded_file($tmp_name, '../' . $folder . $filename);
      }

      if (updateProductImages($folder, $images['name'], $id)) {
        set_message("Le produit a été modifié avec succès !", "success");
      } else {
        set_message("Échec de la mise à jour du produit.", "error");
      }
    }

    redirect('views/admin/products');
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center">
    <form class="settings-form" method="post" enctype="multipart/form-data">
      <h1>Modifier un Produit</h1>
      <h2>Mettez à Jour les Détails du Produit selon Vos Besoins.</h2>
      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="hidden" name="old_images" value="<?= htmlspecialchars($product['images']) ?>">
      <div>
        <label class="label">
          Titre
        </label>
        <input value="<?= post_old_value('title') ? post_old_value('title') : $product['title'] ?>" placeholder="" type="text" name="title" class="input">
      </div>
      <?= isset($errors['title']) ? '<div class="error">' . $errors['title'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Description
        </label>
        <textarea placeholder="" type="text" name="description" class="input"><?= post_old_value('description') ? post_old_value('description') : $product['description'] ?></textarea>
      </div>
      <?= isset($errors['description']) ? '<div class="error">' . $errors['description'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Categorie
        </label>
        <select style="width: 100%;" name="category" class="input">
          <option value="">Choisissez une catégorie</option>
          <?php foreach ($categories as $category) : ?>
            <option value="<?= $category['id'] ?>" <?= $category['id'] == (post_old_value('category') || $product['id']) ? 'selected' : '' ?>><?= $category['title'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?= isset($errors['category']) ? '<div class="error">' . $errors['category'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Marque
        </label>
        <input value="<?= post_old_value('brand') ? post_old_value('brand') : $product['brand'] ?>" placeholder="" type="text" name="brand" class="input">
      </div>
      <?= isset($errors['brand']) ? '<div class="error">' . $errors['brand'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Stock
        </label>
        <input value="<?= post_old_value('stock') ? post_old_value('stock') : $product['stock'] ?>" placeholder="" type="text" name="stock" class="input">
      </div>
      <?= isset($errors['stock']) ? '<div class="error">' . $errors['stock'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Prix
        </label>
        <input value="<?= post_old_value('price') ? post_old_value('price') : $product['price'] ?>" placeholder="" type="text" name="price" class="input">
      </div>
      <?= isset($errors['price']) ? '<div class="error">' . $errors['price'] . '</div>' : '' ?>
      <div>
        <label class="label">
          Pourcentage de réduction
        </label>
        <input value="<?= post_old_value('discount_percentage') ? post_old_value('discount_percentage') : $product['discount_percentage'] ?>" placeholder="" type="text" name="discount_percentage" class="input">
      </div>
      <?= isset($errors['discount_percentage']) ? '<div class="error">' . $errors['discount_percentage'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Images
        </label>
        <input style='font-family: "Space Grotesk", sans-serif; font-size: 16px;' type="file" name="images[]" multiple>
      </div>
      <?= isset($errors['images']) ? '<div class="error">' . $errors['images'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Modifier</button>
    </form>
</main>
</body>

</html>