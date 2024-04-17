<?php
require '../../inc/navbar.php';
$title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
?>

<main class="products-container">
  <div class="search-container">
    <input type="text" name="search" class="input" placeholder="Rechercher...">
    <div class="search-icon"><button style="background: none;border: none; cursor: pointer;"><i class="fa-solid fa-magnifying-glass fa-lg" style="color: #080100;"></i></button></div>
  </div>
  <div class="products">
    <div class="product">
      <div class="product-img-div">
        <img class="product-img" src="<?= ROOT ?>/images/product.png" alt="">
        <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> Category</div>
        <div class="product-img-icon">
          <button class="icon-button"><i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i></button>
        </div>
      </div>
      <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 55 ? substr($title, 0, 55) . '...' : $title ?></a>
      <p class="review"><i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)</p>
      <div class="product-icon-container">
        <div>
          <button class="icon-button" style="border-color: #080100;"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-xl"></i></button>
        </div>
        <div class="product-price">250 dh</div>
      </div>
    </div>
    <div class="product">
      <div class="product-img-div">
        <img class="product-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> Category</div>
        <div class="product-img-icon">
          <button class="icon-button"><i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i></button>
        </div>
      </div>
      <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 55 ? substr($title, 0, 55) . '...' : $title ?></a>
      <p class="review"><i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)</p>
      <div class="product-icon-container">
        <div>
          <button class="icon-button"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-xl"></i></button>
        </div>
        <div class="product-price-container">
          <p class="discount-price">400dh</p>
          <p class="product-price">250dh</p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require '../../inc/footer.php' ?>
