<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // $errors = [];
  $title = addslashes($_POST['title']);
  $description = addslashes($_POST['description']);
  $stock = addslashes($_POST['stock']);
  $category_id = addslashes($_POST['category_id']);

  // if (empty($title)) {
  //   $errors['title'] = "Title is required";
  // } else if (!preg_match("/[a-zA-Z 0-9 \-\_]+$/", trim($title))) {
  //   $errors['title'] = "Title can't have special caracters";
  // }

  $folder = "uploads/";
  if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
  }

  // $image_string = $file_string = "";

  if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (in_array($_FILES['image']['type'], $allowed)) {

      // $image = $folder . $_FILES['image']['name'];
      $image_string = ", image = '$image' ";
    }
  }
  //   } else {
  //     $errors['image'] = "Image type not supported";
  //   }
  // } else {
  //   // if ($mode == 'new')
  //   $errors['image'] = "An image is required";
  // }



  // if (empty($errors)) {

  // if (!empty($image)) {
    move_uploaded_file($_FILES['image']['tmp_name'], $image);


    $query = "insert into songs (user_id,file,image,title,date) values ('$user_id','$file','$image','$title','$date')";


    query($query);
  // }
}

?>

<?php require 'header.php' ?>

<div class="class_22">
  <form method="post" enctype="multipart/form-data" class="class_23">
    <h1 class="class_18">
      <?php if ($mode == 'edit') : ?>
        Edit Song
      <?php elseif ($mode == 'delete') : ?>
        <span>Delete Song</span>
        <div style="color:red;font-size: 18px;">Are you sure you want to delete this song?!</div>
      <?php else : ?>
        Upload Song
      <?php endif; ?>
    </h1>
    <!-- <div style="color: red; padding: 10px;">
      <?php
      if (!empty($errors)) {
        echo implode("<br>", $errors);
      }
      ?>
    </div> -->
    <!-- <?php if ($display) : ?> -->
      <div class="class_24">
        <label class="class_25">
          Title
        </label>
        <input placeholder="" type="text" name="title" class="class_12">
      </div>
      <div class="class_26">
        <img class="class_27 js-image">
        <input onchange="display_image(this.files[0])" type="file" name="image" class="class_28">
      </div>
      <div class="class_26">
        <div class="class_29">
          <audio controls="" class="class_30 js-file">
            <source src="<?= $song['file'] ?? '' ?>" type="audio/mpeg">
          </audio>
        </div>
        <!-- this refers to the input it has a property called files, [0] is to grab the first file selected -->
        <input onchange="load_file(this.files[0])" type="file" name="file">
      </div>
      <div class="class_31">
        <button class="class_32">
          <?= $button_title ?>
        </button>
        <a href="profile.php">
          <button type="button" class="class_33">
            Cancel
          </button>
        </a>
        <div class="class_34">
        </div>
      </div>
    <!-- <?php else : ?>
      <div style="color:red;text-align:center;">That song was not found!</div>
    <?php endif; ?> -->
  </form>
</div>
<!-- <?php require "footer.php" ?> -->

<script>
  function display_image(file) {
    // putting the uploaded image from the input in the src of the image and converting it to base 64
    document.querySelector(".js-image").src = URL.createObjectURL(file);
  }

  function load_file(file) {
    // putting the uploaded image from the input in the src of the image and converting it to base 64
    document.querySelector(".js-file").src = URL.createObjectURL(file);
  }
</script>








<!-- <?php
require '../../inc/navbar.php';
$title = "title";
$product_id = $_GET['id'] ?? 0;
// sanitizing
$product_id = (int)$product_id;

if ($product_id > 0) {
  // Prepare the statement to fetch product details along with category title
  if ($stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?")) {
    $stmt->bind_param("i", $product_id);  // Bind the integer parameter for the product ID
    $stmt->execute();  // Execute the prepared statement
    $result = $stmt->get_result();  // Get the result of the query

    $product_details = $result->fetch_assoc();  // Fetch results as an associative array

    // Check if product details exist
    if ($product_details) {
      // Prepare the statement to fetch all images for the product
      if ($image_stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?")) {
        $image_stmt->bind_param("i", $product_id);
        $image_stmt->execute();
        $image_result = $image_stmt->get_result();

        $images = [];
        while ($row = $image_result->fetch_assoc()) {
          $images[] = $row['title'];  // Append each image title to the images array
        }

        $product_details['images'] = $images;  // Add images array to the product details
      }
    }
  } else {
    echo "Prepared Statement Error: " . $con->error;
  }
}

// echo '<pre>';
// print_r($product_details);
// echo '</pre>';
?> -->

<!-- <?php
require '../../inc/navbar.php';
$title = "title";
$product_id = $_GET['id'] ?? 0;
// sanitizing
$product_id = (int)$product_id;

if ($product_id > 0) {
    // Fetch product details along with category title
    if ($stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?")) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product_details = $result->fetch_assoc();

        // Fetch images for the product
        if ($product_details && ($image_stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?"))) {
            $image_stmt->bind_param("i", $product_id);
            $image_stmt->execute();
            $image_result = $image_stmt->get_result();
            $images = [];
            while ($row = $image_result->fetch_assoc()) {
                $images[] = $row['title'];
            }
            $product_details['images'] = $images;
        }

        // Fetch reviews for the product
        if ($review_stmt = $con->prepare("SELECT r.text, r.user_id FROM reviews r WHERE r.product_id = ?")) {
            $review_stmt->bind_param("i", $product_id);
            $review_stmt->execute();
            $review_result = $review_stmt->get_result();
            $reviews = [];
            while ($row = $review_result->fetch_assoc()) {
                $reviews[] = $row;
            }
            $product_details['reviews'] = $reviews;
        }

        // Fetch similar products from the same category
        if ($similar_stmt = $con->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4")) {
            $similar_stmt->bind_param("ii", $product_details['category_id'], $product_id);
            $similar_stmt->execute();
            $similar_result = $similar_stmt->get_result();
            $similar_products = [];
            while ($row = $similar_result->fetch_assoc()) {
                $similar_products[] = $row;
            }
            $product_details['similar_products'] = $similar_products;
        }
    } else {
        echo "Prepared Statement Error: " . $con->error;
    }
}

echo '<pre>';
print_r($product_details);
echo '</pre>';
?> -->





<!-- ------------------------------------------------ -->
if ($product_id > 0) {
  // Fetch product details along with category title
  $stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $product_details = $stmt->get_result()->fetch_assoc();

  if ($product_details) {
    // Fetch images for the product
    $image_stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?");
    $image_stmt->bind_param("i", $product_id);
    $image_stmt->execute();
    $image_result = $image_stmt->get_result();
    $product_details['images'] = [];
    while ($row = $image_result->fetch_assoc()) {
      $product_details['images'][] = $row['title'];
    }

    // Fetch reviews for the product including user names
    $review_stmt = $con->prepare("SELECT r.text,r.rating, u.firstname, u.lastname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ?");
    $review_stmt->bind_param("i", $product_id);
    $review_stmt->execute();
    $review_result = $review_stmt->get_result();
    $product_details['reviews'] = [];
    while ($review = $review_result->fetch_assoc()) {
      $product_details['reviews'][] = $review;
    }

    // Fetch similar products from the same category with their category titles
    $similar_stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 10");
    $similar_stmt->bind_param("ii", $product_details['category_id'], $product_id);
    $similar_stmt->execute();
    $similar_result = $similar_stmt->get_result();
    $product_details['similar_products'] = [];
    while ($similar = $similar_result->fetch_assoc()) {
      $similar['images'] = [];
      // Fetch images for each similar product
      $image_stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?");
      $image_stmt->bind_param("i", $similar['id']);
      $image_stmt->execute();
      $images_result = $image_stmt->get_result();
      while ($img = $images_result->fetch_assoc()) {
        $similar['images'][] = $img['title'];
      }
      $product_details['similar_products'][] = $similar;
    }
  }
}





// function getAllProducts()
// {
//   $query = "SELECT p.*, c.title AS category_title
//           FROM products p
//           LEFT JOIN categories c ON p.category_id = c.id
//           ORDER BY p.id;";
//   $products = query($query);

//   // Initialize an array to store products with an additional images key
//   $products_with_images = [];

//   foreach ($products as $product) {
//     // Initialize each product with an empty images array
//     $product['images'] = [];
//     $products_with_images[$product['id']] = $product;
//   }

//   // Only proceed if we have products
//   if (!empty($products_with_images)) {
//     $product_ids = array_keys($products_with_images);
//     $id_list = implode(',', $product_ids);

//     $image_query = "SELECT i.product_id, i.title AS image_title
//                     FROM images i
//                     WHERE i.product_id IN ($id_list);";
//     $images = query($image_query);

//     // Loop over images and append them to their respective products
//     foreach ($images as $image) {
//       $products_with_images[$image['product_id']]['images'][] = $image['image_title'];
//     }
//   }
//   return $products_with_images;
// }




// function getSimilarProducts($category_id, $product_id)
// {
//   global $con;
//   $similar_products = [];
//   $similar_stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 10");
//   $similar_stmt->bind_param("ii", $category_id, $product_id);
//   $similar_stmt->execute();
//   $similar_result = $similar_stmt->get_result();
//   while ($similar = $similar_result->fetch_assoc()) {
//     $similar['images'] = [];
//     $image_stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?");
//     $image_stmt->bind_param("i", $similar['id']);
//     $image_stmt->execute();
//     $images_result = $image_stmt->get_result();
//     while ($img = $images_result->fetch_assoc()) {
//       $similar['images'][] = $img['title'];
//     }
//     $similar_products[] = $similar;
//   }
//   return $similar_products;
// }




// Capture filter inputs
$search = isset($_GET['search']) ? $_GET['search'] : '';
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : [];
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : null;
$ratingRanges = [
  (isset($_GET['rating_1_2']) && $_GET['rating_1_2'] == 'on') ? [1, 2] : null,
  (isset($_GET['rating_2_3']) && $_GET['rating_2_3'] == 'on') ? [2, 3] : null,
  (isset($_GET['rating_3_4']) && $_GET['rating_3_4'] == 'on') ? [3, 4] : null,
  (isset($_GET['rating_4_5']) && $_GET['rating_4_5'] == 'on') ? [4, 5] : null,
];
$ratingFilter = array_filter($ratingRanges);  // Remove null entries

// Fetch products based on filters
$products_with_images = getAllProducts($search, $brandFilter, $minPrice, $maxPrice, $ratingFilter);

// Fetch all brands for the filter list
$brands = query("SELECT DISTINCT brand FROM products ORDER BY brand");







// categories:id,title
// images:id,title	,product_id	
// products:id,title	,description	,brand	,stock	,price	,category_id
// reviews:id,user_id,product_id,text,rating




// function getAllProducts()
// {
//   global $con; // Ensure that your database connection variable is accessible
//   $query = "SELECT p.*, c.title AS category_title
//             FROM products p
//             LEFT JOIN categories c ON p.category_id = c.id
//             ORDER BY p.id;";
//   $products = query($query);

//   // Initialize an array to store products with additional keys
//   $products_with_images = [];

//   foreach ($products as $product) {
//     // Initialize each product with an empty images array and default rating details
//     $product['images'] = [];
//     $product['rating_details'] = ['average_rating' => null, 'rating_count' => 0];
//     $products_with_images[$product['id']] = $product;
//   }

//   // Only proceed if we have products
//   if (!empty($products_with_images)) {
//     // id of the products in $products array so we can select there respective images
//     $product_ids = array_keys($products_with_images);
//     $id_list = implode(',', $product_ids);

//     // Fetch images for each product
//     $image_query = "SELECT i.product_id, i.title AS image_title
//                       FROM images i
//                       WHERE i.product_id IN ($id_list);";
//     $images = query($image_query);

//     // Append images to their respective products
//     foreach ($images as $image) {
//       // we use the product id to put the images on it's rescpective products
//       // [1(product id)][images][array of the images]
//       $products_with_images[$image['product_id']]['images'][] = $image['image_title'];
//     }

//     // Fetch rating details for each product
//     $rating_query = "SELECT product_id, AVG(rating) AS average_rating, COUNT(*) AS rating_count
//                        FROM reviews
//                        WHERE product_id IN ($id_list)
//                        GROUP BY product_id;";
//     $ratings = query($rating_query);

//     // Append rating details to their respective products
//     foreach ($ratings as $rating) {
//       $products_with_images[$rating['product_id']]['rating_details'] = [
//         'average_rating' => round($rating['average_rating'], 1), // Round the average rating to one decimal place
//         'rating_count' => (int) $rating['rating_count']
//       ];
//     }
//   }

//   return $products_with_images;
// }








//-------------------------------
<?php
require '../../inc/navbar.php';
// out of stock tag
// Le produit sélectionné n'est plus en stock
// Votre panier est vide, ajoutez des produits pour continuer

// Cart operations
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    ['stock' => $stock] = getProductByID($product_id);
    $quantity = $_POST['quantity'] ?? 1; // Default quantity to 1 if not specified

    if ($stock < $quantity) {
      set_message('La quantité demandée dépasse le stock disponible!', 'warning');
      header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
      exit;
    }
    if ($stock === 0) {
      set_message('Le produit sélectionné n\'est plus en stock !', 'error');
      header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
      exit;
    }

    // Check if the cart session exists
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Add or update the product in the cart
    if (!isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = $quantity;
      set_message('Ce produit a été ajouté à votre panier.', 'success');
    } else {
      // $_SESSION['cart'][$product_id] = min($stock, $_SESSION['cart'][$product_id] + $quantity);
      // set_message('La quantité de ce produit dans votre panier a été mise à jour.', 'success');
      set_message('Ce produit existe déjà dans votre panier!', 'error');
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
    exit;
  } elseif (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);  // Remove the item from the cart
    }
  } elseif (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = [];  // Empty the cart
  } else if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] = max(1, $new_quantity);  // Ensure quantity doesn't go below 1
    }
  }
}
// print_r($_SESSION['cart']);
?>

<main class="cart">
  <div class="cart-container">
    <div class="cart-heading">
      <h2>Panier <span>(<?= count($_SESSION['cart'] ?? []) ?> produit<?= count($_SESSION['cart'] ?? []) > 1 ? 's' : '' ?>)</span></h2>
      <form method="post">
        <button name="empty_cart" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i> Vider le panier</button>
      </form>
    </div>
    <?php if (!empty($_SESSION['cart'])) : ?>
      <?php foreach ($_SESSION['cart'] as $product_id => $quantity) : ?>
        <?php
        $product = getProductByID($product_id);
        $basePrice = $product['discount_percentage'] > 0 ? calculateDiscountPrice($product['price'], $product['discount_percentage']) : $product['price'];
        // echo '<pre>';
        // print_r($product);
        // echo '</pre>';
        ?>
        <div class="cart-product">
          <div class="product-img-title">
            <img style="margin-right: 8px;" class="small-img" src="<?= '../' . $product['images'][0] ?>" alt="">
            <a href="<?= ROOT ?>/views/products/product.php?id=<?= $product['id'] ?>" class="product-title"><?= strlen($product['title']) >= 15 ? substr($product['title'], 0, 15) . '...' : $product['title'] ?></a>
          </div>
          <form method="post" class="product-qty">
            <button type="button" class="icon-button plus"><i style="color: #080100;" class="fa-solid fa-plus fa-xl"></i></button>
            <input type="text" name="quantity" value="<?= $quantity ?>" class="qty-input" onchange="this.form.submit()">
            <button type="button" style="margin-right: 8px;" class="icon-button minus"><i style="color: #080100;" class="fa-solid fa-minus fa-xl"></i></button>

            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <input type="hidden" name="update_quantity" value="1">
            <button type="submit" style="display:none;">Update</button>
          </form>
          <div class="product-price-cart">
            <p class="product-price">
              <?= $basePrice * $quantity ?>dh
            </p>
          </div>
          <div>
            <form method="post">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <button type="submit" name="remove" class="red-btn-regular"><i class="fa-solid fa-x fa-sm"></i></button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <div style="height:26vh; width: 100%; display:flex; align-items: center; justify-content: center;">
        <p>Votre panier est vide !</p>
      </div>
    <?php endif; ?>
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
<script src="<?= ROOT ?>/js/cart.js" defer></script>

<?php require '../../inc/footer.php' ?>