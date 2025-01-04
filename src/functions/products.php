<?php
function getAllProducts($search = '', $minPrice = 0, $maxPrice = 0, $brands = [], $ratings = [])
{
  global $con;

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

function getAllProductsAdmin()
{
  global $con;
  $products = [];
  $sql = "SELECT * FROM products";
  $res = $con->query($sql);
  while ($row = $res->fetch_assoc()) {
    $stmt = $con->prepare('SELECT title FROM categories where id = ?');
    $stmt->bind_param('i', $row['category_id']);
    $stmt->execute();
    $row['category_title'] = $stmt->get_result()->fetch_assoc()['title'];
    $row['images'] = fetchProductImages($row['id']);
    $products[] = $row;
  }
  return $products;
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
    $product_details['images'] = fetchProductImages($product_id);
  }
  return $product_details;
}

function getProductReviews($product_id)
{
  global $con;
  $reviews = [];
  $review_stmt = $con->prepare("SELECT r.id, r.text, r.rating, u.firstname, u.lastname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ?");
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
    $similar['images'] = fetchProductImages($similar['id']);
    $similar['rating_details'] = getProductRatingDetails($similar['id']);
    $similar_products[$similar['id']] = $similar;
    $similar_product_ids[] = $similar['id'];
  }
  return $similar_products;
}

function calculateDiscountPrice($originalPrice, $discountPercentage)
{
  $discountAmount = ($discountPercentage / 100) * $originalPrice;
  return  $originalPrice - $discountAmount;
}

function getProductRatingDetails($product_id)
{
  global $con;

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

function isInWishlist($product_id)
{
  return in_array($product_id, $_SESSION['wishlist'] ?? []);
}

function getProductPrice($product_id)
{
  global $con;

  $stmt = $con->prepare("SELECT price, discount_percentage FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $price = (float)$row['price'];
    $discount_percentage = (float)$row['discount_percentage'];

    // Calculate the final price after applying the discount
    if ($discount_percentage > 0) {
      $discount_amount = ($discount_percentage / 100) * $price;
      $price -= $discount_amount;
    }

    return $price;  // Return the price after applying discount
  }
  return null;  // Return null if no product is found
}

function updateProductStock($product_id, $quantity_sold)
{
  global $con;
  $stmt = $con->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
  $stmt->bind_param("ii", $quantity_sold, $product_id);
  $stmt->execute();
  return $stmt->affected_rows > 0;
}

function insertReview($product_id, $user_id, $rating, $rating_text)
{
  global $con;
  $stmt = $con->prepare('INSERT INTO reviews (product_id,user_id,rating,text) VALUES(?, ?, ?, ?)');

  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return false;
  }

  $stmt->bind_param('iiss', $product_id, $user_id, $rating, $rating_text);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $stmt->close();
    return true;
  } else {
    echo "Error inserting order item: " . $stmt->error;
    $stmt->close();
    return false;
  }
}

function insertProduct($title, $description, $category, $brand, $stock, $price)
{
  global $con;

  $stmt = $con->prepare("INSERT into products (title, description, category_id, brand, stock, price) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param('ssisid', $title, $description, $category, $brand, $stock, $price);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    return false;
  }
  return $con->insert_id;
}

function insertProductImages($folder, $images, $product_id)
{
  global $con;

  foreach ($images as $image) {
    $stmt = $con->prepare("INSERT into images (title, product_id) VALUES (?,?)");
    $title = $folder . $image;
    $stmt->bind_param('si', $title, $product_id);
    $stmt->execute();
  }

  if ($stmt->affected_rows == 0) {
    return false;
  }
  return $con->insert_id;
}

function updateProductImages($folder, $images, $product_id)
{
  global $con;

  // Delete old images for the product
  $stmt_delete = $con->prepare("DELETE FROM images WHERE product_id = ?");
  $stmt_delete->bind_param('i', $product_id);
  $stmt_delete->execute();

  if (insertProductImages($folder, $images, $product_id) > 0) {
    return true;
  } else {
    return false;
  }
}

function updateProduct($id, $title, $description, $category, $brand, $stock, $price, $discount_percentage)
{
  global $con;

  $stmt = $con->prepare("UPDATE products set title= ?, description = ?, category_id = ?, brand=?, stock=?, price =?, discount_percentage = ? WHERE id = ?");
  $stmt->bind_param('ssisiddi', $title, $description, $category, $brand, $stock, $price, $discount_percentage, $id);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    return false;
  }
  return true;
}

function deleteProduct($id)
{
  global $con;
  $image_stmt = $con->prepare("DELETE from images where product_id = ?");
  $image_stmt->bind_param('i', $id);
  $image_stmt->execute();
  if ($image_stmt->affected_rows > 0) {
    $product_stmt = $con->prepare("DELETE FROM products where id = ?");
    $product_stmt->bind_param('i', $id);
    $product_stmt->execute();
    if ($product_stmt->affected_rows > 0) return true;
  }
  return false;
}

function getProductStockLevelsData()
{
  global $con;

  $data = [];

  $sql = "SELECT c.title AS category, SUM(p.stock) AS total_stock
          FROM categories c
          LEFT JOIN products p ON c.id = p.category_id
          GROUP BY c.title";

  $result = $con->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[$row['category']] = $row['total_stock'];
    }
  }

  return $data;
}

function deleteReview($id)
{
  global $con;
  $stmt = $con->prepare("DELETE from reviews where id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  if ($stmt->affected_rows == 0)  return false;
  return true;
}
