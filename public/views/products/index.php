<?php
// if (!empty($products_with_images)) {
//   echo '<pre>';
//   print_r($products_with_images);
//   echo '</pre>';
// } else {
//   echo "No product found!";
// }

require '../../inc/navbar.php';
$allBrands = query("SELECT DISTINCT brand FROM products ORDER BY brand");

$search = $_GET['search'] ?? '';
$brands = $_GET['brand'] ?? [];
$minPrice = $_GET['minPrice'] ?? 0; // Default to 0 if not provided
$maxPrice = $_GET['maxPrice'] ?? 10000;
$ratingFilter = []; // You need to construct this based on your checkbox inputs

// Example of preparing rating filter based on checkboxes (simplified)
if (isset($_GET['rating1to2'])) $ratingFilter[] = [1, 2];
if (isset($_GET['rating2to3'])) $ratingFilter[] = [2, 3];
if (isset($_GET['rating3to4'])) $ratingFilter[] = [3, 4];
if (isset($_GET['rating4to5'])) $ratingFilter[] = [4, 5];

$consolidatedRatingRange = consolidateRatingRange($ratingFilter);

// echo $search;
// echo $minPrice;
// echo $maxPrice;
// print_r($brands);
// echo '<pre>';
// print_r($ratingFilter);
// echo '</pre>';
// echo '<pre>';
// print_r($consolidatedRatingRange);
// echo '</pre>';

$products_with_images = getAllProducts($search, $minPrice, $maxPrice, $brands, $consolidatedRatingRange);


?>
<form method="get" class="products-main">
  <div class="filter-container">
    <h2>Filtrer les Produits</h2>
    <div class="wrapper">
      <p class="filter-heading">Prix</p>
      <div class="price-input">
        <div class="field">
          <span>Min</span>
          <input type="number" class="input-min input" name="minPrice" value="<?= htmlspecialchars($_GET['minPrice'] ?? '0') ?>">
        </div>
        <div class="separaor">-</div>
        <div class="field">
          <span>Max</span>
          <!-- <input type="number" class="input-max input" value="7500"> -->
          <input type="number" class="input-max input" name="maxPrice" value="<?= htmlspecialchars($_GET['maxPrice'] ?? '10000') ?>">
        </div>
      </div>
      <div class="slider">
        <div class="progress"></div>
      </div>
      <div class="range-input">
        <input type="range" min="0" max="10000" class="range-min" value="<?= htmlspecialchars($_GET['minPrice'] ?? '0') ?>" step="100" name="minPrice">
        <input type="range" min="0" max="10000" class="range-max" value="<?= htmlspecialchars($_GET['maxPrice'] ?? '10000') ?>" step="100" name="maxPrice">
      </div>
    </div>

    <div style="margin-bottom: 8px;" class="brand">
      <p class="filter-heading">Marque</p>
      <ul class="brand-list">
        <!-- <?php foreach ($allBrands as $b) : ?>
          <li><input type="checkbox" name="brand[]" value="<?= $b['brand'] ?>"><?= $b['brand'] ?></li>
        <?php endforeach ?> -->
        <?php foreach ($allBrands as $brand) : ?>
          <li>
            <input type="checkbox" name="brand[]" value="<?= htmlspecialchars($brand['brand']) ?>" <?= in_array($brand['brand'], $brands) ? 'checked' : '' ?>><?= htmlspecialchars($brand['brand']) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="brand">
      <p class="filter-heading">Avis</p>
      <ul class="brand-list">
        <!-- <li style="padding-top: 0px;"><input type="checkbox" name="rating1to2">1 à 2 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating2to3">2 à 3 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating3to4">3 à 4 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating4to5">4 à 5 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
      </ul> -->
        <li><input type="checkbox" name="rating1to2" <?= isset($_GET['rating1to2']) ? 'checked' : '' ?>>1 à 2 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating2to3" <?= isset($_GET['rating2to3']) ? 'checked' : '' ?>>2 à 3 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating3to4" <?= isset($_GET['rating3to4']) ? 'checked' : '' ?>>3 à 4 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
        <li><input type="checkbox" name="rating4to5" <?= isset($_GET['rating4to5']) ? 'checked' : '' ?>>4 à 5 <i style="color:#FAE264" class="fa-solid fa-star"></i></li>
      </ul>
    </div>
    <div class="filter-btn-container">
      <button type="submit" class="primary-btn-small">Appliquer</button>
      <button type="button" class="secondary-btn-small clear">Effacer</button>
    </div>
  </div>

  <div class="products-container">
    <div class="search-container">
      <input value="<?= get_old_value('search') ?>" type="text" name="search" class="input" placeholder="Rechercher une catégorie...">
      <div class="search-icon"><button type="submit" style="background: none;border: none; cursor: pointer;"><i class="fa-solid fa-magnifying-glass fa-lg" style="color: #080100;"></i></button></div>
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
              <?= $product['rating_details']['average_rating'] == null ? '<span class="review">Pas encore d\'avis</span>' : $product['rating_details']['average_rating'] . ' (' . $product['rating_details']['rating_count'] . ')' ?>
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
        <div style="height:60vh; width: 100%; display:flex; align-items: center; justify-content: center;">
          <p>Aucun produit trouvé !</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</form>

<script src="<?= ROOT ?>/js/range.js" defer></script>
<script src="<?= ROOT ?>/js/clear.js" defer></script>

<?php require '../../inc/footer.php' ?>