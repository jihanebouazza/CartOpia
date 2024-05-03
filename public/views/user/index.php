<?php
require '../../inc/header.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

?>
<main>
  <?php require '../../inc/user_sidebar.php'; ?>
  <div class="user-content">
    contentt
  </div>
</main>
</body>

</html>