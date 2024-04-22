<?php
require '../../inc/navbar.php';
$title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
?>

<main class="cart">
  <div class="cart-container">
    <div class="cart-heading">
      <h2>Panier <span>(2 produits)</span></h2>
      <button class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i> Vider le panier</button>
    </div>
    <div class="cart-product">
      <div class="product-img-title">
        <img style="margin-right: 8px;" class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 15 ? substr($title, 0, 15) . '...' : $title ?></a>
      </div>
      <div class="product-qty">
        <button class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
        <input type="text" value="0" class="qty-input">
        <button style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>
      </div>
      <div class="product-price-cart">
        <p class="product-price">250dh</p>
      </div>
      <div><button class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i></button></div>
    </div>
    <div class="cart-product">
      <div class="product-img-title">
        <img style="margin-right: 8px;" class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 15 ? substr($title, 0, 15) . '...' : $title ?></a>
      </div>
      <div class="product-qty">
        <button class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
        <input type="text" value="0" class="qty-input">
        <button style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>
      </div>
      <div class="product-price-cart">
        <p class="product-price">250dh</p>
      </div>
      <div><button class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i></button></div>
    </div>
  </div>
  <div class="total-container">
    <h2>Résumé de la commande</h2>
    <div style="margin: 16px 0px 8px;" class="hr"></div>
    <div class="total thin">
      <p>Sous-total</p>
      <p>250 dh</p>
    </div>
    <div style="margin-top: 4px;" class="total thin">
      <p>Livraison</p>
      <p>0 dh</p>
    </div>
    <div style="margin: 8px 0px 8px;" class="hr"></div>
    <div style="font-weight: 700;" class="total">
      <p>Total</p>
      <p>250 dh</p>
    </div>
    <button style="width: 100%;margin-top: 16px;" class="primary-btn">Passer à la caisse</button>
  </div>
</main>
<script src="<?= ROOT ?>/js/quantity.js" defer></script>

<?php require '../../inc/footer.php' ?>