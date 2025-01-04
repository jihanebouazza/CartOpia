<?php
require './inc/navbar.php';
?>

<div class="hero">
  <div class="hero-text">
    <h1 class="hero-header">Your <span>Unique</span> Destination for Everything You <br />
      <span>Need</span> and <span>Desire</span>.
    </h1>
  </div>
  <a href="<?= ROOT ?>/views/products/"><button class="primary-btn">Explore Our Products</button></a>
  <div>
    <img class="hero-img" src="<?= ROOT ?>/images/home-imageT.png" />
  </div>
</div>
<section class="first-section">
  <h2>Services</h2>
  <div class="services">
    <div class="card">
      <div style="background-color: #ffeeea;" class="card-circle">
        <i style="color:#6E1309" class="fa-solid fa-truck fa-lg icon"></i>
      </div>
      <div>
        <p>Fast Delivery</p>
        <p class="card-description">Quick and Reliable</p>
      </div>
    </div>
    <div class="card">
      <div style="background-color: #FFE1CC;" class="card-circle">
        <i style="color:#652C03" class="fa-solid fa-rotate-left fa-lg"></i>
      </div>
      <div>
        <p>Easy Returns</p>
        <p class="card-description">Satisfaction Guaranteed</p>
      </div>
    </div>
    <div class="card">
      <div style="background-color: #DAFECD;" class="card-circle">
        <i style="color:#154D01" class="fa-regular fa-credit-card fa-lg"></i>
      </div>
      <div>
        <p>Secure Payments</p>
        <p class="card-description">Guaranteed Security</p>
      </div>
    </div>
    <div class="card">
      <div style="background-color: #FFD7D7;" class="card-circle">
        <i style="color:#760101" class="fa-solid fa-user-tie fa-lg"></i>
      </div>
      <div>
        <p>Excellent customer service</p>
        <p class="card-description">Exceptional Support</p>
      </div>
    </div>
  </div>
</section>
<section class="second-section">
  <h2>Our Satisfied Customers</h2>
  <div class="testemonials">
    <div style="position: relative;" class="testemonial1">
      <div class="client" style="color: #1C0301; background-color: rgba(250, 163, 100, 0.2)">Emily</div>
      <p>"I am extremely satisfied with my shopping experience at CartOpia. Not only is the product quality exceptional, but the customer service also exceeds my expectations! The fast delivery and hassle-free returns make shopping here a breeze."</p>
    </div>
    <div class="testemonial2">
      <div style="position: relative; height: 100%;">
        <img src="<?= ROOT ?>/images/homebg.jpg" class="bg-img" />
        <div class="client">Robert</div>
        <div class="overlay"></div>
        <p>"I've been a loyal customer for years and have never been disappointed by CartOpia. High-quality products, fast delivery, and excellent customer service make this company my first choice for online shopping."</p>
      </div>
    </div>
  </div>
</section>
<?php require './inc/footer.php' ?>