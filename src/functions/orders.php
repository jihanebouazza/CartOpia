<?php
function insertOrder($user_id, $total, $payment_type, $payment_status)
{
  global $con;

  $stmt = $con->prepare("INSERT INTO orders (user_id, total, payment_type,payment_status, date) VALUES (?, ?, ?,?, NOW())");
  $stmt->bind_param("idss", $user_id, $total, $payment_type, $payment_status);
  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    return false;
  }
  return $con->insert_id;
}

function insertOrderItem($order_id, $product_id, $quantity, $price)
{
  global $con;

  $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
  $stmt = $con->prepare($sql);

  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return false;
  }

  $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);

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

function getAllOrdersByUserId($userId)
{
  global $con;

  $stmt = $con->prepare("SELECT id, user_id, total, status, payment_type, payment_status, date
          FROM orders
          WHERE user_id = ?");

  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return null;
  }

  $stmt->bind_param("i", $userId);
  $stmt->execute();

  $result = $stmt->get_result();

  $orders = [];
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }

  $stmt->close();
  return $orders;
}

function getAllOrders()
{
  global $con;

  $sql = "SELECT * FROM orders";

  $result = $con->query($sql);

  if (!$result) {
    echo "Error: " . $con->error;
    return [];
  }

  $orders = [];
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }
  return $orders;
}

function getOrderById($orderId)
{
  global $con;

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

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return null;
  }

  $stmt->bind_param("i", $orderId);
  $stmt->execute();

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
  global $con;
  $orderCount = 0;

  $stmt = $con->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();

  $stmt->bind_result($orderCount);
  $stmt->fetch();

  $stmt->close();

  return $orderCount;
}

function getOrderStatusCounts($userId)
{
  global $con;
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

function deleteOrderByID($id)
{
  global $con;

  $items_stmt = $con->prepare("DELETE FROM order_items WHERE order_id = ?");
  $items_stmt->bind_param('i', $id);
  $items_stmt->execute();

  if ($items_stmt->affected_rows >= 0) {
    $orders_stmt = $con->prepare("DELETE FROM orders WHERE id = ?");
    $orders_stmt->bind_param('i', $id);
    $orders_stmt->execute();
    if ($orders_stmt->affected_rows > 0) {
      return true;
    }
  }
  return false;
}


function updateOrderStatus($id, $order_status, $payment_status)
{
  global $con;

  $stmt = $con->prepare("UPDATE orders set status= ?, payment_status = ? WHERE id = ?");
  $stmt->bind_param('ssi', $order_status, $payment_status, $id);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    return false;
  }
  return true;
}
