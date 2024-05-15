<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $title = htmlspecialchars($_POST['title']);
  $description = htmlspecialchars($_POST['description']);

  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $title) && !strlen($title) >= 3) {
    $errors['title'] = 'Le titre est Invalide!';
  }
  if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $description) && !strlen($description) >= 10) {
    $errors['description'] = 'La description est Invalide!';
  }

  if (empty($errors)) {
    if (insertCategory($title, $description)) {
      set_message("La catégorie a été ajouter avec succès !", "success");
      redirect('views/admin/categories');
    }
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content flex-center" style="height: 90vh;">
    <form class="settings-form" method="post" enctype="multipart/form-data">
      <h1>Ajouter une Catégorie</h1>
      <h2>Remplissez les détails ci-dessous pour ajouter une nouvelle catégorie.</h2>
      <div>
        <label class="label">
          Titre
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

      <button class="primary-btn" style="width: 100%; margin-top:16px" type="submit">Ajouter</button>
    </form>
</main>
</body>

</html>