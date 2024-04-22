<?php
require '../../inc/navbar.php';
$title = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
?>

<main>
  <div class="single-product-container">
    <div class="product-images">
      <img class="main-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
      <div class="small-images">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
        <img class="small-img" src="<?= ROOT ?>/images/product1.jpeg" alt="">
      </div>
    </div>
    <div class="product-infos">
      <h2 style=""><?= $title ?></h2>
      <p style="margin: 8px 0px;">
        <i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)
      </p>
      <div class="product-icon-container">
        <div class="product-price-container">
          <p class="product-price">250dh</p>
          <p class="discount-price">400dh</p>
        </div>
        <div>
          <button class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
          <input type="text" value="0" class="qty-input">
          <button style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>
          <button class="secondary-btn-small"><i style="color: #ff988d;" class="fa-solid fa-cart-shopping fa-lg"></i> Ajouter au panier</button>
          <button class="icon-button">
            <i style="color: #C51818;" class="fa-regular fa-heart fa-xl"></i></button>
        </div>
      </div>
      <p style="font-weight: 700; padding: 8px 0px;">Description</p>
      <div style="margin-bottom: 8px;" class="hr"></div>
      <p style="text-align: justify;">Donec elit mi, maximus nec efficitur in, convallis sit amet nisl. Ut faucibus scelerisque venenatis. Praesent vel erat quis sapien dapibus imperdiet non eget est. Donec vestibulum suscipit mauris. Sed pellentesque nisl in mi sollicitudin, sit amet mattis dui molestie. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent a sapien tellus. In rutrum tempor lacus sit amet iaculis. Curabitur a nulla et ante hendrerit vehicula et in nunc. Nunc faucibus massa a vestibulum scelerisque. Suspendisse et tortor posuere, fringilla erat in, congue orci.</p>
    </div>
  </div>
  <div class="reviews-section">
    <h2>Ce que disent nos clients</h2>
    <div class="reviews-container">
      <div class="single-review">
        <p style="font-weight: 700;">John Doe</p>
        <p style="margin-top: 4px;" class="review">
          <i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)
        </p>
        <p>Donec elit mi, maximus nec efficitur in, convallis sit amet nisl. Ut faucibus scelerisque venenatis. Praesent vel erat quis sapien dapibus imperdiet non eget est. Donec vestibulum suscipit mauris. Sed pellentesque nisl in mi sollicitudin, sit amet mattis dui molestie. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent a sapien tellus. In rutrum tempor lacus sit amet iaculis. Curabitur a nulla et ante hendrerit vehicula et in nunc. Nunc faucibus massa a vestibulum scelerisque. Suspendisse et tortor posuere, fringilla erat in, congue orci.</p>
      </div>
      <div class="single-review">
        <p style="text-align: center; font-weight: 700;">John Doe</p>
        <p style="text-align: center; margin-top: 4px;" class="review">
          <i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)
        </p>
        <p>Donec elit mi, maximus nec efficitur in, convallis sit amet nisl. Ut faucibus scelerisque venenatis. Praesent vel erat quis sapien dapibus imperdiet non eget est. Donec vestibulum suscipit mauris. Sed pellentesque nisl in mi sollicitudin, sit amet mattis dui molestie. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent a sapien tellus. In rutrum tempor lacus sit amet iaculis. Curabitur a nulla et ante hendrerit vehicula et in nunc. Nunc faucibus massa a vestibulum scelerisque. Suspendisse et tortor posuere, fringilla erat in, congue orci.</p>
      </div>
      <div class="single-review">
        <p style="text-align: center; font-weight: 700;">John Doe</p>
        <p style="text-align: center; margin-top: 4px;" class="review">
          <i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)
        </p>
        <p>Donec elit mi, maximus nec efficitur in, convallis sit amet nisl. Ut faucibus scelerisque venenatis. Praesent vel erat quis sapien dapibus imperdiet non eget est. Donec vestibulum suscipit mauris. Sed pellentesque nisl in mi sollicitudin, sit amet mattis dui molestie. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent a sapien tellus. In rutrum tempor lacus sit amet iaculis. Curabitur a nulla et ante hendrerit vehicula et in nunc. Nunc faucibus massa a vestibulum scelerisque. Suspendisse et tortor posuere, fringilla erat in, congue orci.</p>
      </div>
      <div class="single-review">
        <p style="text-align: center; font-weight: 700;">John Doe</p>
        <p style="text-align: center; margin-top: 4px;" class="review">
          <i style="color:#FAE264" class="fa-solid fa-star"></i> 4.5 (14)
        </p>
        <p>Donec elit mi, maximus nec efficitur in, convallis sit amet nisl. Ut faucibus scelerisque venenatis. Praesent vel erat quis sapien dapibus imperdiet non eget est. Donec vestibulum suscipit mauris. Sed pellentesque nisl in mi sollicitudin, sit amet mattis dui molestie. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent a sapien tellus. In rutrum tempor lacus sit amet iaculis. Curabitur a nulla et ante hendrerit vehicula et in nunc. Nunc faucibus massa a vestibulum scelerisque. Suspendisse et tortor posuere, fringilla erat in, congue orci.</p>
      </div>
    </div>
  </div>
  <div class="suggestions-section">
    <h2>Vous aimerez peut-être aussi</h2>
    <div class="suggestions-container">
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
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/js/quantity.js" defer></script>
<?php require '../../inc/footer.php' ?>