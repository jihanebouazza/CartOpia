<?php
function getAllProducts($search = '', $minPrice = 0, $maxPrice = 0, $brands = [], $ratings = [])
{
  global $con; // Assuming $con is your mysqli connection

  $conditions = [];
  $params = [];
  $param_types = '';

  // Search condition
  if (!empty($search)) {
    $conditions[] = "c.title LIKE ?";
    $params[] = '%' . $search . '%';
    $param_types .= 's';
  }

  // Price filters
  if ($minPrice > 0) {
    $conditions[] = "p.price >= ?";
    $params[] = $minPrice;
    $param_types .= 'd';
  }
  if ($maxPrice > 0) {
    $conditions[] = "p.price <= ?";
    $params[] = $maxPrice;
    $param_types .= 'd';
  }

  // Brand filter
  if (!empty($brands)) {
    $placeholders = implode(',', array_fill(0, count($brands), '?'));
    $conditions[] = "p.brand IN ($placeholders)";
    $params = array_merge($params, $brands);
    $param_types .= str_repeat('s', count($brands));
  }

  // // Rating filter
  // if (!empty($ratings)) {
  //   $ratingConditions = [];
  //   foreach ($ratings as $rating) {
  //     if (is_array($rating) && count($rating) == 2) { // Ensure the array has exactly two elements
  //       $ratingConditions[] = "(SELECT AVG(rating) FROM reviews WHERE product_id = p.id) BETWEEN ? AND ?";
  //       $params[] = $rating[0]; // Min rating
  //       $params[] = $rating[1]; // Max rating
  //       $param_types .= 'dd'; // Two decimal parameters
  //     }
  //   }
  //   if (!empty($ratingConditions)) {
  //     $conditions[] = '(' . implode(' OR ', $ratingConditions) . ')';
  //   }
  // }

  // // Assemble the query
  // $query = "SELECT p.*, c.title AS category_title FROM products p LEFT JOIN categories c ON p.category_id = c.id";
  // if (!empty($conditions)) {
  //   $query .= " WHERE " . implode(' AND ', $conditions);
  // }
  // $query .= " ORDER BY p.id;";
      // Rating filter - applying the consolidated rating range
      if (!empty($ratings) && count($ratings) == 2) {
        $conditions[] = "(SELECT AVG(r.rating) FROM reviews r WHERE r.product_id = p.id) BETWEEN ? AND ?";
        $params[] = $ratings[0]; // Minimum rating
        $params[] = $ratings[1]; // Maximum rating
        $param_types .= 'dd'; // Adding two double parameters
    }

    // Assemble the query
    $query = "SELECT p.*, c.title AS category_title FROM products p LEFT JOIN categories c ON p.category_id = c.id";
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }
    $query .= " ORDER BY p.id;";

  // Prepare the query
  $stmt = $con->prepare($query);

  // Bind parameters
  if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
  }

  // Execute and fetch results
  $stmt->execute();
  $result = $stmt->get_result();
  $products = $result->fetch_all(MYSQLI_ASSOC);

  // Initialize an array to store products with additional details
  $products_with_images = [];

  foreach ($products as $product) {
    $product['images'] = [];
    $product['rating_details'] = ['average_rating' => null, 'rating_count' => 0];
    $products_with_images[$product['id']] = $product;
  }

  // Only proceed if we have products
  if (!empty($products_with_images)) {
    $product_ids = array_keys($products_with_images);
    $id_list = implode(',', $product_ids);

    // Fetch images for each product
    $image_query = "SELECT i.product_id, i.title AS image_title FROM images i WHERE i.product_id IN ($id_list);";
    $images_result = $con->query($image_query);

    while ($image = $images_result->fetch_assoc()) {
      $products_with_images[$image['product_id']]['images'][] = $image['image_title'];
    }

    // Fetch rating details for each product
    $rating_query = "SELECT product_id, AVG(rating) AS average_rating, COUNT(*) AS rating_count FROM reviews WHERE product_id IN ($id_list) GROUP BY product_id;";
    $ratings_result = $con->query($rating_query);

    while ($rating = $ratings_result->fetch_assoc()) {
      $products_with_images[$rating['product_id']]['rating_details'] = [
        'average_rating' => round($rating['average_rating'], 1),
        'rating_count' => (int) $rating['rating_count']
      ];
    }
  }

  $stmt->close();
  return $products_with_images;
}



function fetchProductImages($product_id)
{
  global $con;
  $images = [];
  $stmt = $con->prepare("SELECT title FROM images WHERE product_id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $images[] = $row['title'];
  }
  return $images;
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


function getSimilarProducts($category_id, $product_id)
{
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

function get_old_value($key, $default = "")
{
  if (isset($_GET[$key]) && !empty($_GET[$key])) {
    return $_GET[$key];
  }
  return $default;
}

function consolidateRatingRange(array $ratingRanges)
{
  if (empty($ratingRanges)) return null;

  $minRating = PHP_INT_MAX;
  $maxRating = PHP_INT_MIN;

  foreach ($ratingRanges as $range) {
    $minRating = min($minRating, $range[0]);
    $maxRating = max($maxRating, $range[1]);
  }

  return [$minRating, $maxRating];
}
