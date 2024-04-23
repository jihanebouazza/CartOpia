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