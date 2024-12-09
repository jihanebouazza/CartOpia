<?php

function getUserSpendingPerCategory($userId)
{
  global $con;

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

function getAllCategories()
{
  global $con;
  $categories = [];

  $sql = "SELECT * FROM categories";
  $res = $con->query($sql);
  while ($row = $res->fetch_assoc()) {
    $categories[] = $row;
  }
  return $categories;
}

function deleteCategory($id)
{
  global $con;
  $stmt = $con->prepare("DELETE from categories where id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  if ($stmt->affected_rows == 0)  return false;
  return true;
}

function insertCategory($title, $description)
{
  global $con;
  $stmt = $con->prepare("INSERT into categories (title, category_description) VALUES (?,?)");
  $stmt->bind_param('ss', $title, $description);
  $stmt->execute();
  if ($stmt->affected_rows == 0)  return false;
  return true;
}

function updateCategory($id, $title, $description)
{
  global $con;

  $stmt = $con->prepare("UPDATE categories SET title = ?, category_description = ? WHERE id = ?");
  $stmt->bind_param('ssi', $title, $description, $id);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    return false;
  }
  return true;
}

function getCategoryById($id)
{
  global $con;

  $stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();

  $result = $stmt->get_result();

  return $result->fetch_assoc();
}


function getCategoryDistributionData()
{
  global $con;

  $data = [];

  $sql = "SELECT c.title AS category, COUNT(p.id) AS product_count
          FROM categories c
          LEFT JOIN products p ON c.id = p.category_id
          GROUP BY c.title";

  $result = $con->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[$row['category']] = $row['product_count'];
    }
  }

  return $data;
}