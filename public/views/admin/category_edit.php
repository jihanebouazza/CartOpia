<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$errors = [];
$id = $_GET['id'] ?? 0;
$category = getCategoryById($id);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $id = htmlspecialchars($_POST['id']);
  $title = htmlspecialchars($_POST['title']);
  $description = htmlspecialchars($_POST['description']);

  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $title) && !strlen($title) >= 3) {
    $errors['title'] = 'The title is invalid!';
  }
  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $description) && !strlen($description) >= 10) {
    $errors['description'] = 'The description is invalid!';
  }

  if (empty($errors)) {
    if (updateCategory($id, $title, $description)) {
      set_message("The category has been successfully updated!", "success");
      redirect('views/admin/categories');
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post" enctype="multipart/form-data">
      <h1>Edit a Category</h1>
      <h2>Update the category details as needed.</h2>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div>
        <label class="label">
          Title
        </label>
        <input value="<?= post_old_value('title') ? post_old_value('title') : $category['title'] ?>" placeholder="" type="text" name="title" class="input">
      </div>
      <?= isset($errors['title']) ? '<div class="error">' . $errors['title'] . '</div>' : '' ?>

      <div>
        <label class="label">
          Description
        </label>
        <textarea placeholder="" type="text" name="description" class="input"><?= post_old_value('description') ? post_old_value('description') : $category['category_description'] ?></textarea>
      </div>
      <?= isset($errors['description']) ? '<div class="error">' . $errors['description'] . '</div>' : '' ?>

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Edit</button>
    </form>
</main>
</body>

</html>