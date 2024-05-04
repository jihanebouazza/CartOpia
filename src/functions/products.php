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
function post_old_value($key, $default = "")
{
  if (isset($_POST[$key]) && !empty($_POST[$key])) {
    return $_POST[$key];
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

function isInWishlist($product_id)
{
  return in_array($product_id, $_SESSION['wishlist'] ?? []);
}

function getProductPrice($product_id)
{
  global $con; // Ensure you have a global connection variable available

  // Prepare the SQL statement to select the price and discount percentage
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

function insertOrder($user_id, $total, $payment_type, $payment_status)
{
  global $con; // Database connection variable

  // Corrected SQL statement: removed an extra placeholder and matched the parameters
  $stmt = $con->prepare("INSERT INTO orders (user_id, total, payment_type,payment_status, date) VALUES (?, ?, ?,?, NOW())");

  // Ensure the correct types are used in bind_param:
  // 'i' for integer, 'd' for double (float), 's' for string
  $stmt->bind_param("idss", $user_id, $total, $payment_type, $payment_status);

  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    return false; // Order not inserted
  }
  return $con->insert_id; // Return the new order ID
}

function updateProductStock($product_id, $quantity_sold)
{
  global $con;
  $stmt = $con->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
  $stmt->bind_param("ii", $quantity_sold, $product_id);
  $stmt->execute();
  return $stmt->affected_rows > 0;
}
function insertOrderItem($order_id, $product_id, $quantity, $price)
{
  global $con; // Ensure that $con is your database connection variable

  // Prepare the SQL query to insert an order item
  $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
  $stmt = $con->prepare($sql);

  if (!$stmt) {
    // Handle error here if prepare failed
    echo "Error preparing statement: " . $con->error;
    return false;
  }

  // Bind the parameters to the SQL query
  $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);

  // Execute the query
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $stmt->close();
    return true; // Return true on success
  } else {
    // Handle error here if insertion failed
    echo "Error inserting order item: " . $stmt->error;
    $stmt->close();
    return false;
  }
}

function getAllOrdersByUserId($userId)
{
  global $con;  // Ensure that $con is your database connection variable

  // Prepare SQL to fetch all orders for a specific user
  $sql = "SELECT id, user_id, total, status, payment_type, payment_status, date
          FROM orders
          WHERE user_id = ?";

  // Prepare the SQL statement
  $stmt = $con->prepare($sql);
  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return null;
  }

  // Bind the user_id parameter
  $stmt->bind_param("i", $userId);
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  $orders = [];
  while ($row = $result->fetch_assoc()) {
    $orders[] = [
      'id' => $row['id'],
      'user_id' => $row['user_id'],
      'total' => $row['total'],
      'status' => $row['status'],
      'payment_type' => $row['payment_type'],
      'payment_status' => $row['payment_status'],
      'date' => $row['date']
    ];
  }

  $stmt->close();
  return $orders;
}

// function getAllOrdersByUserId($userId)
// {
//   global $con;  // Ensure that $con is your database connection variable

//   // Prepare SQL to fetch orders with associated order items and product details, filtered by user_id
//   $sql = "SELECT o.id AS order_id, o.user_id, o.total, o.status, o.payment_type, o.payment_status, o.date,
//                  oi.id AS order_item_id, oi.quantity, oi.price,
//                  p.id AS product_id, p.title, p.description, p.brand, p.stock, p.price AS product_price, c.title AS category_title
//           FROM orders o
//           JOIN order_items oi ON o.id = oi.order_id
//           JOIN products p ON oi.product_id = p.id
//           JOIN categories c ON p.category_id = c.id
//           WHERE o.user_id = ?
//           ORDER BY o.date DESC";  // Sorting by the most recent orders first

//   // Prepare the SQL statement
//   $stmt = $con->prepare($sql);
//   if (!$stmt) {
//     echo "Error preparing statement: " . $con->error;
//     return [];
//   }

//   // Bind the user_id parameter
//   $stmt->bind_param("i", $userId);
//   $stmt->execute();

//   // Get the result
//   $result = $stmt->get_result();

//   $orders = [];
//   while ($row = $result->fetch_assoc()) {
//     $order_id = $row['order_id'];

//     // Initialize order if not already set
//     if (!isset($orders[$order_id])) {
//       $orders[$order_id] = [
//         'order_id' => $order_id,
//         'user_id' => $row['user_id'],
//         'total' => $row['total'],
//         'status' => $row['status'],
//         'payment_type' => $row['payment_type'],
//         'payment_status' => $row['payment_status'],
//         'date' => $row['date'],
//         'order_items' => []
//       ];
//     }

//     // Append order item and product details to the order
//     $orders[$order_id]['order_items'][] = [
//       'order_item_id' => $row['order_item_id'],
//       'quantity' => $row['quantity'],
//       'price' => $row['price'],
//       'product' => [
//         'product_id' => $row['product_id'],
//         'title' => $row['title'],
//         'description' => $row['description'],
//         'brand' => $row['brand'],
//         'stock' => $row['stock'],
//         'price' => $row['product_price'],
//         'category_title' => $row['category_title']
//       ]
//     ];
//   }

//   $stmt->close();
//   return $orders;
// }

// function getAllOrders() {
//   global $con;  // Ensure that $con is your database connection variable

//   // SQL to fetch orders with associated order items and product details
//   $sql = "SELECT o.id AS order_id, o.user_id, o.total, o.status, o.payment_type, o.payment_status, o.date,
//                  oi.id AS order_item_id, oi.quantity, oi.price,
//                  p.id AS product_id, p.title, p.description, p.brand, p.stock, p.price AS product_price, c.title AS category_title
//           FROM orders o
//           JOIN order_items oi ON o.id = oi.order_id
//           JOIN products p ON oi.product_id = p.id
//           JOIN categories c ON p.category_id = c.id
//           ORDER BY o.date DESC";  // Assuming you want the most recent orders first

//   $result = $con->query($sql);

//   if (!$result) {
//       // Handle error - the SQL query failed
//       echo "Error: " . $con->error;
//       return [];
//   }

//   $orders = [];
//   while ($row = $result->fetch_assoc()) {
//       $order_id = $row['order_id'];

//       // Initialize order if not already set
//       if (!isset($orders[$order_id])) {
//           $orders[$order_id] = [
//               'order_id' => $order_id,
//               'user_id' => $row['user_id'],
//               'total' => $row['total'],
//               'status' => $row['status'],
//               'payment_type' => $row['payment_type'],
//               'payment_status' => $row['payment_status'],
//               'date' => $row['date'],
//               'order_items' => []
//           ];
//       }

//       // Append order item and product details to the order
//       $orders[$order_id]['order_items'][] = [
//           'order_item_id' => $row['order_item_id'],
//           'quantity' => $row['quantity'],
//           'price' => $row['price'],
//           'product' => [
//               'product_id' => $row['product_id'],
//               'title' => $row['title'],
//               'description' => $row['description'],
//               'brand' => $row['brand'],
//               'stock' => $row['stock'],
//               'price' => $row['product_price'],
//               'category_title' => $row['category_title']
//           ]
//       ];
//   }

//   return $orders;
// }

// function getOrderById($orderId)
// {
//   global $con;  // Ensure that $con is your database connection variable

//   // Prepare SQL to fetch a specific order with associated order items and product details
//   $sql = "SELECT o.id AS order_id, o.user_id, o.total, o.status, o.payment_type, o.payment_status, o.date,
//                  oi.id AS order_item_id, oi.quantity, oi.price,
//                  p.id AS product_id, p.title, p.description, p.brand, p.stock, p.price AS product_price, c.title AS category_title
//           FROM orders o
//           JOIN order_items oi ON o.id = oi.order_id
//           JOIN products p ON oi.product_id = p.id
//           JOIN categories c ON p.category_id = c.id
//           WHERE o.id = ?
//           ORDER BY o.date DESC";

//   // Prepare the SQL statement
//   $stmt = $con->prepare($sql);
//   if (!$stmt) {
//     echo "Error preparing statement: " . $con->error;
//     return null;
//   }

//   // Bind the order_id parameter
//   $stmt->bind_param("i", $orderId);
//   $stmt->execute();

//   // Get the result
//   $result = $stmt->get_result();

//   $order = null;
//   while ($row = $result->fetch_assoc()) {
//     if ($order === null) {
//       $order = [
//         'order_id' => $row['order_id'],
//         'user_id' => $row['user_id'],
//         'total' => $row['total'],
//         'status' => $row['status'],
//         'payment_type' => $row['payment_type'],
//         'payment_status' => $row['payment_status'],
//         'date' => $row['date'],
//         'order_items' => []
//       ];
//     }

//     // Append order item and product details to the order
//     $order['order_items'][] = [
//       'order_item_id' => $row['order_item_id'],
//       'quantity' => $row['quantity'],
//       'price' => $row['price'],
//       'product' => [
//         'product_id' => $row['product_id'],
//         'title' => $row['title'],
//         'description' => $row['description'],
//         'brand' => $row['brand'],
//         'stock' => $row['stock'],
//         'price' => $row['product_price'],
//         'category_title' => $row['category_title']
//       ]
//     ];
//   }

//   $stmt->close();
//   return $order;
// }

function getOrderById($orderId)
{
  global $con;  // Ensure that $con is your database connection variable

  // Prepare SQL to fetch a specific order with associated order items, product details, and user details
  $sql = "SELECT o.id AS order_id, o.user_id, o.total, o.status, o.payment_type, o.payment_status, o.date,
                 oi.id AS order_item_id, oi.quantity, oi.price,
                 p.id AS product_id, p.title, p.description, p.brand, p.stock, p.discount_percentage, p.price AS product_price, c.title AS category_title,
                 u.firstname, u.lastname, u.email, u.address, u.city,u.phone_number, u.postal_code
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          JOIN categories c ON p.category_id = c.id
          JOIN users u ON o.user_id = u.id
          WHERE o.id = ?
          ORDER BY o.date DESC";

  // Prepare the SQL statement
  $stmt = $con->prepare($sql);
  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return null;
  }

  // Bind the order_id parameter
  $stmt->bind_param("i", $orderId);
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  $order = null;
  while ($row = $result->fetch_assoc()) {
    if ($order === null) {
      $order = [
        'order_id' => $row['order_id'],
        'user_id' => $row['user_id'],
        'total' => $row['total'],
        'status' => $row['status'],
        'payment_type' => $row['payment_type'],
        'payment_status' => $row['payment_status'],
        'date' => $row['date'],
        'user_details' => [
          'firstname' => $row['firstname'],
          'lastname' => $row['lastname'],
          'email' => $row['email'],
          'address' => $row['address'],
          'city' => $row['city'],
          'phone_number' => $row['phone_number'],
          'postal_code' => $row['postal_code'],
        ],
        'order_items' => []
      ];
    }

    // Append order item and product details to the order
    $order['order_items'][] = [
      'order_item_id' => $row['order_item_id'],
      'quantity' => $row['quantity'],
      'price' => $row['price'],
      'product' => [
        'product_id' => $row['product_id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'brand' => $row['brand'],
        'stock' => $row['stock'],
        'price' => $row['product_price'],
        'category_title' => $row['category_title'],
        'discount_percentage' => $row['discount_percentage'],
      ]
    ];
  }

  $stmt->close();
  return $order;
}

function getNumberOfOrdersByUser($userId)
{
  global $con; // Make sure you have defined your $con variable as your database connection

  // Prepare the SQL statement to count orders by user ID
  $stmt = $con->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();

  // Bind result variables
  $stmt->bind_result($orderCount);
  $stmt->fetch(); // Fetch the count result

  $stmt->close();

  return $orderCount; // Return the number of orders
}

function getUserSpendingPerCategory($userId)
{
  global $con;  // Your database connection variable

  $sql = "SELECT c.title AS category, SUM(oi.price * oi.quantity) AS total_spent
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          JOIN categories c ON p.category_id = c.id
          WHERE o.user_id = ?
          GROUP BY c.title
          ORDER BY total_spent DESC";

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return [];
  }
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  $categorySpending = [];
  while ($row = $result->fetch_assoc()) {
    $categorySpending[] = [
      'category' => $row['category'],
      'total_spent' => $row['total_spent']
    ];
  }
  $stmt->close();
  return $categorySpending;
}
function getOrderStatusCounts($userId)
{
  global $con; // Assuming $con is your mysqli database connection variable
  $sql = "SELECT status, COUNT(*) as count FROM orders WHERE user_id = ? GROUP BY status";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[$row['status']] = $row['count'];
  }
  return $data;
}
