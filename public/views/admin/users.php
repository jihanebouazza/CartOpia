<?php
require '../../inc/header.php';

if (!is_admin()) {
  redirect('views/user/index');
}

$users = getAllUsers();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = htmlspecialchars($_POST['id']);
  if (deleteUserById($id)) {
    set_message('Utilisateur supprimmer avec succès!', 'success');
    header('Location:' . $_SERVER['HTTP_REFERER']);
  }
}
?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>

  <div class="user-content">
    <h2>Tous les utlisateurs</h2>
    <?php if (!empty($users)) : ?>
      <table style="margin-top: 16px;" class="table">
        <thead>
          <tr>
            <th>Num</th>
            <th>Nom Complet</th>
            <th>Email</th>
            <th>Télephone</th>
            <th>Adresse</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) : ?>
            <tr>
              <td><?= $user['id'] ?></td>
              <td><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
              <td><?= $user['email'] ?></td>
              <td><?= $user['phone_number'] ?></td>
              <td><?= $user['address'] . ', ' . $user['city'] . ', ' . $user['postal_code'] ?></td>
              <td><?= $user['role'] ?></td>
              <td>
                <a href="<?= ROOT ?>/views/admin/user_edit.php?id=<?= $user['id'] ?>" style="margin: 0px 4px 0px 0px; color: #A19796; border-color: #A19796;padding: 4px 4px 4px 6px;" class="icon-button">
                  <i style="color: #A19796;" class="fa-solid fa-pen-to-square fa-sm"></i>
                </a>
                <form method="post" style="display: inline-block;" action="">
                  <input type="hidden" name="id" value="<?= $user['id'] ?>">
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
        <p>Aucun utilisateur trouvé !</p>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>

</html>