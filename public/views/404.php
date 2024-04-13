<?php
require '../inc/navbar.php';
?>
<main class="not-found-container">
  <h1>Erreur 404 - Page non trouvée</h1>
  <div>
    <img src="<?= ROOT ?>/images/404_bg-removebg-preview.png" alt="">
  </div>
  <p>Désolé, la page que vous recherchez n'existe pas.</p>
  <a href="<?= ROOT ?>/"><button class="secondary-btn">
      <i class="fa-solid fa-arrow-left-long" style="margin-right: 4px;"></i>
      Retour à la page d'accueil</button></a>

</main>
</body>

</html>