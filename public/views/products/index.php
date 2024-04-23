<?php
require '../../inc/navbar.php';

$products_with_images = getAllProducts();
// if (!empty($products_with_images)) {
//   echo '<pre>';
//   print_r($products_with_images);
//   echo '</pre>';
// } else {
//   echo "No product found!";
// }
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

      <?php if (!empty($products_with_images)) : ?>

        <?php foreach ($products_with_images as $product) : ?>
          <div class="product">
            <div class="product-img-div">
              <img class="product-img" src="<?= '../' . $product['images'][0] ?>" alt="">
              <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> <?= $product['category_title'] ?></div>
              <div class="product-img-icon">
                <button class="icon-button"><i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i></button>
              </div>
            </div>
            <a href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="product-title"><?= strlen($product['title']) >= 50 ? substr($product['title'], 0, 50) . '...' :  $product['title']  ?></a>
            <p class="review"><i style="color:#FAE264" class="fa-solid fa-star"></i>
              <?= $product['rating_details']['average_rating'] == null ? '<span class="review">Pas encore d\'avis</span>' : $product['rating_details']['average_rating'] . '(' . $product['rating_details']['rating_count'] . ')' ?>
            </p>
            <div class="product-icon-container">
              <div>
                <button class="icon-button"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-xl"></i></button>
              </div>
              <div class="product-price-container">

                <?= $product['discount_percentage'] > 0 ? ' <p class="discount-price">' . $product['price'] . 'dh</p>' . '<p class="product-price">' . calculateDiscountPrice($product['price'],  $product['discount_percentage']) . 'dh</p>'  : '<p class="product-price">' . $product['price'] . 'dh</p>' ?>

              </div>
            </div>
          </div>
        <?php endforeach; ?>

      <?php else : ?>
        <div style="text-align: center; width: 100%;">
          <p>Aucun produit trouvé !</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/range.js" defer></script>
<?php require '../../inc/footer.php' ?>