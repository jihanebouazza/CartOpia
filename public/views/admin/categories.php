<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$categories = getAllCategories();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = htmlspecialchars($_POST['id']);
  if (deleteCategory($id)) {
    set_message('Catégorie supprimmer avec succès!', 'success');
    header('Location:' . $_SERVER['HTTP_REFERER']);
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content">
    <div class="flex">
      <h2>Tous les catégories</h2>
      <a href="<?= ROOT ?>/views/admin/category_add.php"><button class="primary-btn-small "><i style="color: #fffdfd;" class="fa-solid fa-circle-plus fa-sm"></i> Ajouter une catégorie</button></a>
    </div>
    <?php if (!empty($categories)) : ?>
      <table style="margin-top: 16px;" class="table">
        <thead>
          <tr>
            <th>Num</th>
            <th>Titre</th>
            <th>Description</th>
            <th style="text-align: center;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category) : ?>
            <tr>
              <td><?= $category['id'] ?></td>
              <td><?= $category['title'] ?></td>
              <td style="width: 60%;"><?= $category['category_description'] ?></td>
              <td  style="text-align: center;">
                <a href="<?= ROOT ?>/views/admin/category_edit.php?id=<?= $category['id'] ?>" style="margin: 0px 4px 0px 0px; color: #A19796; border-color: #A19796; padding: 4px 4px 4px 6px;" class="icon-button">
                  <i style="color: #A19796;" class="fa-solid fa-pen-to-square fa-sm"></i>
                </a>
                <form method="post" style="display: inline-block;" action="">
                  <input type="hidden" name="id" value="<?= $category['id'] ?>">
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
        <p>Aucune catégorie trouvé !</p>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>

</html>