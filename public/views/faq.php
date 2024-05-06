<?php
require '../inc/navbar.php';
?>
<main class="faq">
  <h2>Foire Aux Questions</h2>
  <div class="accordion">
    <div class="contentBox">
      <div class="accordion-label">Comment puis-je créer un compte ?</div>
      <div class="accordion-content">
        <p>Pour créer un compte, cliquez sur 'S'inscrire' en haut de la page, puis suivez les instructions pour remplir vos informations personnelles.</p>
      </div>
    </div>
    <div class="contentBox">
      <div class="accordion-label">Comment passer une commande ?</div>
      <div class="accordion-content">
        <p>Pour passer une commande, ajoutez les produits désirés à votre panier, puis procédez au paiement en suivant les étapes de validation de la commande.</p>
      </div>
    </div>
    <div class="contentBox">
      <div class="accordion-label">Y a-t-il une quantité minimum de commande ?</div>
      <div class="accordion-content">
        <p>Non, il n'y a pas de quantité minimum de commande sur notre site. Vous pouvez commander autant ou aussi peu d'articles que vous le souhaitez.</p>
      </div>
    </div>
    <div class="contentBox">
      <div class="accordion-label">Comment puis-je laisser une évaluation pour un produit que j'ai acheté ?</div>
      <div class="accordion-content">
        <p>Vous pouvez visiter la page du produit que vous avez acheté et y laisser votre avis après la réception de votre produit.</p>
      </div>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/accordion.js" defer></script>


<?php require '../inc/footer.php'; ?>