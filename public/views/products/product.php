<?php
require '../../inc/navbar.php';
$title = "title";
$product_id = $_GET['id'] ?? 0;
$product_id = (int)$product_id;

if ($product_id > 0) {
  $product_details = getProductByID($product_id);
  $product_rating_details = getProductRatingDetails($product_id);

  if (!empty($product_details)) {
    $product_details['reviews'] = getProductReviews($product_id);
    $product_details['similar_products'] = getSimilarProducts($product_details['category_id'], $product_id);
    $product_details['rating_details'] = getProductRatingDetails($product_id);
  }
}

// if (!empty($product_details)) {
//   echo '<pre>';
//   print_r($product_details);
//   echo '</pre>';
// } else {
//   echo "No product found!";
// }
?>

<main>
  <div class="single-product-container">
    <div class="product-images">
      <img class="main-img" id="main-product-image" src="<?= '../' . $product_details['images'][0] ?>" alt="">
      <div class="small-images">
        <?php foreach ($product_details['images'] as $index => $image) : ?>
          <img class="small-img <?= $index === 0 ? 'selected-image' : '' ?>" src="<?= '../' . $image ?>" onclick="updateMainImage(this, '<?= '../' . $image ?>')" alt="Small Image">
        <?php endforeach; ?>
      </div>
    </div>
    <div class="product-infos">
      <h2><?= $product_details['title'] ?></h2>
      <div class="category"><i class="fa-solid fa-circle fa-2xs"></i> <?= $product_details['category_title'] ?></div>

      <p class="review" style="margin-bottom:8px;">
        <i style="color:#FAE264" class="fa-solid fa-star"></i>
        <?= $product_details['rating_details']['average_rating'] == null ? '<span class="review">Pas encore d\'avis</span>' : $product_details['rating_details']['average_rating'] . ' (' . $product_details['rating_details']['rating_count'] . ')' ?>
      </p>
      <div class="product-icon-container">
        <div class="product-price-container">
          <?= $product_details['discount_percentage'] > 0 ? '<p class="product-price">' . calculateDiscountPrice($product_details['price'],  $product_details['discount_percentage']) . 'dh</p>' . ' <p class="discount-price">' . $product_details['price'] . 'dh</p>' : '<p class="product-price">' . $product_details['price'] . 'dh</p>' ?>
        </div>
        <div>
          <form style="display: inline-block;" method="post" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $product_details['id'] ?>">
            <button type="button" class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
            <input type="text" name="quantity" value="1" class="qty-input">
            <button type="button" style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>
            <button name="add" type="submit" class="secondary-btn-small"><i style="color: #ff988d;" class="fa-solid fa-cart-shopping fa-lg"></i> Ajouter au panier</button>
          </form>
          <button class="icon-button" style="border-color: #C51818;">
            <i style="color: #C51818;" class="fa-regular fa-heart fa-xl"></i></button>
        </div>
      </div>
      <p style="font-weight: 700; padding: 8px 0px;">Description</p>
      <div style="margin-bottom: 8px;" class="hr"></div>
      <p style="text-align: justify;"><?= $product_details['description'] ?></p>
    </div>
  </div>
  <?php if (!empty($product_details['reviews'])) : ?>
    <div class="reviews-section">
      <h2>Ce que disent nos clients</h2>
      <div class="reviews-container">

        <?php foreach ($product_details['reviews'] as $review) : ?>
          <div class="single-review">
            <p style="font-weight: 700;"><?= $review['firstname'] . ' ' . $review['lastname'] ?></p>
            <p style="margin-top: 4px;" class="review">
              <i style="color:#FAE264" class="fa-solid fa-star"></i> <?= $review['rating'] ?>/5
            </p>
            <p><?= $review['text'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($product_details['similar_products'])) : ?>
    <div class="suggestions-section">
      <h2>Vous aimerez peut-être aussi</h2>
      <div class="suggestions-container">
        <?php foreach ($product_details['similar_products'] as $product) : ?>
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
              <form method="post" action="cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button name="add" type="submit" class="icon-button"><i style="color: #080100;" class="fa-solid fa-cart-shopping fa-xl"></i></button>
              </form>
              <div class="product-price-container">
                <?= $product['discount_percentage'] > 0 ? ' <p class="discount-price">' . $product['price'] . 'dh</p>' . '<p class="product-price">' . calculateDiscountPrice($product['price'],  $product['discount_percentage']) . 'dh</p>'  : '<p class="product-price">' . $product['price'] . 'dh</p>' ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</main>

<script src="<?= ROOT ?>/js/quantity.js" defer></script>
<script src="<?= ROOT ?>/js/image.js" defer></script>

<?php require '../../inc/footer.php' ?>