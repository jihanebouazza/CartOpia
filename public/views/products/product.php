<?php
require '../../inc/navbar.php';



$product_id = $_GET['id'] ?? 0;
$product_id = (int)$product_id;
$errors = [];

if ($product_id > 0) {
  $product_details = getProductByID($product_id);
  $product_rating_details = getProductRatingDetails($product_id);

  if (!empty($product_details)) {
    $product_details['reviews'] = getProductReviews($product_id);
    $product_details['similar_products'] = getSimilarProducts($product_details['category_id'], $product_id);
    $product_details['rating_details'] = getProductRatingDetails($product_id);
    if (is_logged_in()) {
      $user_id = user('id');
      $canReview = userCanReview($user_id, $product_id);
    }
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_review'])) {
    $p_id = htmlspecialchars($_POST['p_id']);
    $u_id = htmlspecialchars($_POST['u_id']);
    $rating = htmlspecialchars($_POST['rating']);
    $rating_text = htmlspecialchars($_POST['rating_text']);

    if (empty($rating) || $rating > 5 || $rating < 1) {
      $errors['rating'] = 'Veuillez fournir une note entre 1 et 5.';
      set_message($errors['rating'], 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
    if (!preg_match("/^[\p{L}0-9 ,.'\"\-\–éèêëàâûôîçü]+$/u", $rating_text) && !strlen($rating_text) >= 10) {
      $errors['rating_text'] = 'Le texte de l\'avis doit contenir au moins 10 caractères.';
      set_message($errors['rating_text'], 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
    if (empty($errors)) {
      if (insertReview($p_id, $u_id, $rating, $rating_text)) {
        set_message('Votre avis a été ajouté avec succès.', 'success');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }
  }

  if (isset($_POST['delete_review'])) {
    $id = $_POST['id']; // No need to use htmlspecialchars here
    if (deleteReview($id)) {
        set_message('Avis supprimé avec succès!', 'success');
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit; // Add an exit statement after redirection
    } else {
        set_message('Erreur lors de la suppression de l\'avis.' . $id, 'error'); // Handle error case
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit; // Add an exit statement after redirection
    }
}
}


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
          <form style="display: inline-block;" method="post" action="wishlist.php">
            <input type="hidden" name="product_id" value="<?= $product_details['id'] ?>">
            <!-- <button name="add-to-wishlist" type="submit" class="icon-button" style="border-color: #C51818;">
              <i style="color: #C51818;" class="fa-regular fa-heart fa-xl"></i></button> -->
            <?php if (isInWishlist($product_details['id'])) : ?>
              <button style="border-color: #C51818;" type="submit" name="remove-from-wishlist" class="icon-button">
                <i style="color: #C51818; opacity: 1;" class="fa-solid fa-heart fa-xl"></i> <!-- Solid heart if in wishlist -->
              </button>
            <?php else : ?>
              <button style="border-color: #C51818;" type="submit" name="add-to-wishlist" class="icon-button">
                <i style="color: #C51818; opacity: 1;" class="fa-regular fa-heart fa-xl"></i> <!-- Regular heart if not in wishlist -->
              </button>
            <?php endif; ?>
          </form>
        </div>
      </div>
      <p style="font-weight: 700; padding: 8px 0px;">Description</p>
      <div style="margin-bottom: 8px;" class="hr"></div>
      <p style="text-align: justify;"><?= $product_details['description'] ?></p>
    </div>
  </div>
  <div class="reviews-section">
    <div class="flex">
      <h2>Ce que disent nos clients</h2>
      <?php if (is_logged_in() && $canReview) : ?>
        <button class="secondary-btn-small-review modal-btn"><i style="color:#FAE264" class="fa-solid fa-star"></i> Donner un avis</button>
      <?php endif; ?>
    </div>

    <?php if (!empty($product_details['reviews'])) : ?>
      <div class="reviews-container">
        <?php foreach ($product_details['reviews'] as $review) : ?>
          <div class="single-review">
            <?php if (is_admin()) : ?>
              <div style="position: absolute; top:8px; right:8px">
                <form method="post" style="display: inline-block;" action="">
                  <input type="hidden" name="id" value="<?= $review['id'] ?>">
                  <button name="delete_review" style="cursor:pointer; background: none; border:solid 1px #f56262;padding: 6px 10px;" type="submit" class="icon-button">
                    <i style="color: #f56262;" class="fa-solid fa-trash fa-sm"></i>
                  </button>
                </form>
              </div>
            <?php endif; ?>
            <p style="font-weight: 700;"><?= $review['firstname'] . ' ' . $review['lastname'] ?></p>
            <p style="margin-top: 4px;" class="review">
              <i style="color:#FAE264" class="fa-solid fa-star"></i> <?= $review['rating'] ?>/5
            </p>
            <p><?= $review['text'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div style="height:30vh; width: 100%; display:flex; align-items: center; justify-content: center;">
        <p>Pas encore d'avis.</p>
      </div>
    <?php endif; ?>

  </div>
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

<div class="modal-bg">
  <div class="modal">
    <button class="close modal-close"><i style="color: #080100;" class="fa-solid fa-x fa-lg"></i></button>
    <form method="post">
      <h1>Évaluez Votre Achat!</h1>
      <h2>Aidez-Nous à Améliorer.</h2>
      <input type="hidden" name="p_id" value="<?= $product_id ?>">
      <input type="hidden" name="u_id" value="<?= $user_id ?>">
      <div>
        <label class="login_label">
          Évaluation
        </label>
        <input value="<?= post_old_value('rating') ?>" placeholder="Notez de 1 à 5 étoiles" type="text" name="rating" class="input">
      </div>
      <div>
        <label class="login_label">
          Votre Avis
        </label>
        <textarea class="input" name="rating_text" id=""><?= post_old_value('rating_text') ?></textarea>
      </div>
      <button name="add_review" class="primary-btn" style="width: 100%; margin-top: 8px;" type="submit">Soumettre</button>

    </form>
  </div>
</div>

<script src="<?= ROOT ?>/js/modal.js" defer></script>


<script src="<?= ROOT ?>/js/quantity.js" defer></script>
<script src="<?= ROOT ?>/js/image.js" defer></script>

<?php require '../../inc/footer.php' ?>