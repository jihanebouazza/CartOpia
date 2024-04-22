<?php
require '../../inc/navbar.php';
$title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";


// Fetch products along with their category titles
$query = "SELECT p.*, c.title AS category_title
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          ORDER BY p.id;";
$products = query($query);

// Initialize an array to store products with an additional images key
$products_with_images = [];

foreach ($products as $product) {
  // Initialize each product with an empty images array
  $product['images'] = [];
  $products_with_images[$product['id']] = $product;
}

// Only proceed if we have products
if (!empty($products_with_images)) {
  $product_ids = array_keys($products_with_images);
  $id_list = implode(',', $product_ids);

  $image_query = "SELECT i.product_id, i.title AS image_title
                    FROM images i
                    WHERE i.product_id IN ($id_list);";
  $images = query($image_query);

  // Loop over images and append them to their respective products
  foreach ($images as $image) {
    $products_with_images[$image['product_id']]['images'][] = $image['image_title'];
  }
}

// Now $products_with_images contains all products and their images correctly
echo '<pre>';
print_r($products_with_images);
echo '</pre>';
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
            <!-- <button class="regular-btn"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-lg"></i> Ajouter au panier</button> -->
            <button class="icon-button"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-xl"></i></button>

          </div>
          <div class="product-price">250 dh</div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/range.js" defer></script>
<?php require '../../inc/footer.php' ?>