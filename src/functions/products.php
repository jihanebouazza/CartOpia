<?php
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

function getAllProducts() {
  global $con; // Ensure that your database connection variable is accessible
  $query = "SELECT p.*, c.title AS category_title
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id;";
  $products = query($query);

  // Initialize an array to store products with additional keys
  $products_with_images = [];

  foreach ($products as $product) {
      // Initialize each product with an empty images array and default rating details
      $product['images'] = [];
      $product['rating_details'] = ['average_rating' => null, 'rating_count' => 0];
      $products_with_images[$product['id']] = $product;
  }

  // Only proceed if we have products
  if (!empty($products_with_images)) {
      $product_ids = array_keys($products_with_images);
      $id_list = implode(',', $product_ids);

      // Fetch images for each product
      $image_query = "SELECT i.product_id, i.title AS image_title
                      FROM images i
                      WHERE i.product_id IN ($id_list);";
      $images = query($image_query);

      // Append images to their respective products
      foreach ($images as $image) {
          $products_with_images[$image['product_id']]['images'][] = $image['image_title'];
      }

      // Fetch rating details for each product
      $rating_query = "SELECT product_id, AVG(rating) AS average_rating, COUNT(*) AS rating_count
                       FROM reviews
                       WHERE product_id IN ($id_list)
                       GROUP BY product_id;";
      $ratings = query($rating_query);

      // Append rating details to their respective products
      foreach ($ratings as $rating) {
          $products_with_images[$rating['product_id']]['rating_details'] = [
              'average_rating' => round($rating['average_rating'], 1), // Round the average rating to one decimal place
              'rating_count' => (int) $rating['rating_count']
          ];
      }
  }

  return $products_with_images;
}


function getProductByID($product_id)
{
  global $con;
  $product_details = [];
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
  }
  return $product_details;
}

function getProductReviews($product_id)
{
  global $con;
  $reviews = [];
  $review_stmt = $con->prepare("SELECT r.text, r.rating, u.firstname, u.lastname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ?");
  $review_stmt->bind_param("i", $product_id);
  $review_stmt->execute();
  $review_result = $review_stmt->get_result();
  while ($review = $review_result->fetch_assoc()) {
    $reviews[] = $review;
  }
  return $reviews;
}

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
function getSimilarProducts($category_id, $product_id) {
  global $con;
  $similar_products = [];
  $similar_stmt = $con->prepare("SELECT p.*, c.title AS category_title FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 10");
  $similar_stmt->bind_param("ii", $category_id, $product_id);
  $similar_stmt->execute();
  $similar_result = $similar_stmt->get_result();
  
  $similar_product_ids = [];
  while ($similar = $similar_result->fetch_assoc()) {
      $similar['images'] = [];
      $similar['rating_details'] = ['average_rating' => null, 'rating_count' => 0];
      $similar_products[$similar['id']] = $similar;
      $similar_product_ids[] = $similar['id'];
  }

  // Fetch images for each similar product
  if (!empty($similar_product_ids)) {
      $id_list = implode(',', $similar_product_ids);
      $image_query = "SELECT product_id, title FROM images WHERE product_id IN ($id_list)";
      $images = query($image_query);
      foreach ($images as $image) {
          $similar_products[$image['product_id']]['images'][] = $image['title'];
      }

      // Fetch rating details for each similar product
      $rating_query = "SELECT product_id, AVG(rating) AS average_rating, COUNT(*) AS rating_count FROM reviews WHERE product_id IN ($id_list) GROUP BY product_id";
      $ratings = query($rating_query);
      foreach ($ratings as $rating) {
          $similar_products[$rating['product_id']]['rating_details'] = [
              'average_rating' => round($rating['average_rating'], 1),
              'rating_count' => (int) $rating['rating_count']
          ];
      }
  }

  // Re-index the array to return only the values, without product_ids as keys
  return array_values($similar_products);
}


function calculateDiscountPrice($originalPrice, $discountPercentage)
{
  $discountAmount = ($discountPercentage / 100) * $originalPrice;
  return  $originalPrice - $discountAmount;
}

function getProductRatingDetails($product_id)
{
    global $con;
    
    // Prepare the SQL statement to compute the average rating and count the ratings
    $stmt = $con->prepare("SELECT AVG(rating) AS average_rating, COUNT(rating) AS rating_count FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ratingDetails = $result->fetch_assoc();
    
    $stmt->close();

    if ($ratingDetails['rating_count'] > 0) {
        // Round the average rating to one decimal place
        $ratingDetails['average_rating'] = round($ratingDetails['average_rating'], 1);
    } else {
        // Set default values if no ratings
        $ratingDetails['average_rating'] = null;
        $ratingDetails['rating_count'] = 0;
    }

    return $ratingDetails;
}

