<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$categories = getAllCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $title = htmlspecialchars($_POST['title']);
  $description = htmlspecialchars($_POST['description']);
  $category = htmlspecialchars($_POST['category']);
  $brand = htmlspecialchars($_POST['brand']);
  $stock = htmlspecialchars($_POST['stock']);
  $price = htmlspecialchars($_POST['price']);
  $images = $_FILES['images'];

  $folder = "../../src/uploads/";
  $allowed_ext = ['image/jpeg', 'image/png', 'image/webp'];

  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $title) && !strlen($title) >= 3) {
    $errors['title'] = 'The title is invalid!';
  }
  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $description) && !strlen($description) >= 10) {
    $errors['description'] = 'The description is invalid!';
  }
  if (empty($category)) {
    $errors['category'] = 'The category is required!';
  }
  if (!preg_match("/^[\w\s.,'éàèùâêîôûäëïöüç-]{3,}$/", $brand)) {
    $errors['brand'] = 'The brand is invalid!';
  }
  if ($stock <= 0) {
    $errors['stock'] = 'The stock is invalid!';
  }
  if ($price <= 0) {
    $errors['price'] = 'The price is invalid!';
  }
  foreach ($images['type'] as $image) {
    if (!in_array($image, $allowed_ext)) {
      $errors['images'] = 'The image type is invalid!';
    }
  }

  if (empty($errors)) {
    if ($insert_id = insertProduct($title, $description, $category, $brand, $stock, $price)) {
      // upload
      for ($i = 0; $i < count($images['tmp_name']); $i++) {
        $tmp_name = $images['tmp_name'][$i];
        $filename = $images['name'][$i];
        move_uploaded_file($tmp_name, '../' . $folder . $filename);
      }
      // insert
      if (insertProductImages($folder, $images['name'], $insert_id)) {
        set_message("The product has been successfully added!", "success");
        redirect('views/admin/products');
      }
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center">
    <form class="settings-form" method="post" enctype="multipart/form-data">
      <h1>Add a New Product</h1>
      <h2>Fill in the Product Information to Add It.</h2>
      <div>
        <label class="label">
          Title
        </label>
        <input value="<?= post_old_value('title') ?>" placeholder="" type="text" name="title" class="input">
      </div>
      <?= isset($errors['title']) ? '<div class="error">' . $errors['title'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Description
        </label>
        <textarea placeholder="" type="text" name="description" class="input"><?= post_old_value('description') ?></textarea>
      </div>
      <?= isset($errors['description']) ? '<div class="error">' . $errors['description'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Category
        </label>
        <select style="width: 100%;" name="category" class="input">
          <option value="">Choose a category</option>
          <?php foreach ($categories as $category) : ?>
            <option value="<?= $category['id'] ?>" <?= $category['id'] == post_old_value('category') ? 'selected' : '' ?>><?= $category['title'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?= isset($errors['category']) ? '<div class="error">' . $errors['category'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Brand
        </label>
        <input value="<?= post_old_value('brand') ?>" placeholder="" type="text" name="brand" class="input">
      </div>
      <?= isset($errors['brand']) ? '<div class="error">' . $errors['brand'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Stock
        </label>
        <input value="<?= post_old_value('stock') ?>" placeholder="" type="text" name="stock" class="input">
      </div>
      <?= isset($errors['stock']) ? '<div class="error">' . $errors['stock'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Price
        </label>
        <input value="<?= post_old_value('price') ?>" placeholder="" type="text" name="price" class="input">
      </div>
      <?= isset($errors['price']) ? '<div class="error">' . $errors['price'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Images
        </label>
        <input style='font-family: "Space Grotesk", sans-serif; font-size: 16px;' type="file" name="images[]" multiple>
      </div>
      <?= isset($errors['images']) ? '<div class="error">' . $errors['images'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Add</button>
    </form>
</main>
</body>

</html>