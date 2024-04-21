<?php
require '../../inc/navbar.php';
$title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
?>

<main class="products-main">
  <div class="filter-container">
    <h2>Filtrer les Produits</h2>
    <div class="wrapper">
      <p class="filter-heading">Prix</p>
      <div class="price-input">
        <div class="field">
          <span>Min</span>
          <input type="number" class="input-min input" value="2500">
        </div>
        <div class="separaor">-</div>
        <div class="field">
          <span>Max</span>
          <input type="number" class="input-max input" value="7500">
        </div>
      </div>
      <div class="slider">
        <div class="progress"></div>
      </div>
      <div class="range-input">
        <input type="range" min="0" max="10000" class="range-min" value="2500" step="100">
        <input type="range" min="0" max="10000" class="range-max" value="7500" step="100">
      </div>
    </div>

    <div style="margin-bottom: 8px;" class="brand">
      <p class="filter-heading">Marque</p>
      <ul class="brand-list">
        <li style="padding-top: 0px;"><input type="checkbox">Marque 1</li>
        <li><input type="checkbox">Marque 1</li>
        <li><input type="checkbox">Marque 1</li>
        <li><input type="checkbox">Marque 1</li>
      </ul>
    </div>

    <div class="brand">
      <p class="filter-heading">Avis</p>
      <ul class="brand-list">
        <li style="padding-top: 0px;"><input type="checkbox">1 à 2 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox">2 à 3 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox">3 à 4 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox">4 à 5 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
      </ul>
    </div>
    <div class="filter-btn-container">
      <button type="button" class="primary-btn-small">Appliquer</button>
      <button type="button" class="secondary-btn-small">Effacer</button>
    </div>
  </div>

  <div class="products-container">
    <div class="search-container">
      <input type="text" name="search" class="input" placeholder="Rechercher une catégorie...">
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
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 58 ? substr($title, 0, 58) . '...' : $title ?></a>
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
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 58 ? substr($title, 0, 58) . '...' : $title ?></a>
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
      <div class="product">
        <div class="product-img-div">
          <img class="product-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
          <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> Category</div>
          <div class="product-img-icon">
            <button class="icon-button"><i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i></button>
          </div>
        </div>
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 58 ? substr($title, 0, 58) . '...' : $title ?></a>
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
      <div class="product">
        <div class="product-img-div">
          <img class="product-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
          <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> Category</div>
          <div class="product-img-icon">
            <button class="icon-button"><i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i></button>
          </div>
        </div>
        <a href="<?= ROOT ?>/views/products/product.php/id=1" class="product-title"><?= strlen($title) >= 58 ? substr($title, 0, 58) . '...' : $title ?></a>
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
  </div>
</main>

<script src="<?= ROOT ?>/js/range.js" defer></script>
<?php require '../../inc/footer.php' ?>