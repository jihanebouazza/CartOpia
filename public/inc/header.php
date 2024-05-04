<?php
require_once dirname(__DIR__) . '/../' . 'src/init.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="<?= ROOT ?>/css/index.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/auth.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/user.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/404.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/home.css">
  <link rel="stylesheet" href="<?= ROOT ?>/css/products.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" type="image/png" href="<?= ROOT ?>/images/logo.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Cartopia</title>
</head>

<body>
  <?php $toast = display_toast(); ?>
  <?php if ($toast) : ?>
    <div class="toast animate__animated animate__bounceInDown <?= $toast['type'] ?>">
      <i class="fa-solid <?= $toast['type'] === 'error' ? 'fa-circle-exclamation' : ($toast['type'] === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation') ?>"></i>
      <?= htmlspecialchars($toast['message']) ?>
    </div>
    <script>
      setTimeout(() => {
        const toastElement = document.querySelector('.toast');
        if (toastElement) {
          toastElement.classList.remove('animate__bounceInDown');
          toastElement.classList.add('animate__bounceOutUp');
          setTimeout(() => toastElement.remove(), 1000); // Ensure this duration matches the animation duration
        }
      }, 3000); // Display the toast for 3 seconds before starting the bounce-out animation
    </script>
  <?php endif; ?>