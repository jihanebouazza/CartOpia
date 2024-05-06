<?php
require '../../inc/header.php';
if (!is_admin()) {
  redirect('views/user/index');
}


?>
<main>
  <?php require '../../inc/admin_sidebar.php'; ?>
  <div class="user-content">
    contentt
  </div>
</main>
</body>

</html>