<?php
function getAllUsers()
{
  global $con;
  $users = [];

  $res = $con->query("SELECT * FROM users");
  while ($row = $res->fetch_assoc()) {
    $users[] = $row;
  }

  return $users;
}

function updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)
{
  global $con;

  $stmt = $con->prepare("UPDATE users SET phone_number = ?, address = ?, city = ?, postal_code = ? WHERE id = ?");
  $stmt->bind_param("ssssi", $phone_number, $address, $city, $postal_code, $user_id);
  $stmt->execute();

  // if ($stmt->affected_rows === 0) {
  //   return false; // No rows updated
  // }
  return true;
}

function updateUserProfile($user_id, $first_name, $last_name, $email)
{
  global $con;

  $stmt = $con->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
  $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    if ($_SESSION['USER']['id'] == $user_id) {
      $_SESSION['USER']['firstname'] = $first_name;
      $_SESSION['USER']['lastname'] = $last_name;
      $_SESSION['USER']['email'] = $email;
    }
    return true;
  }
  return false;
}

function getUserPassword($user_id)
{
  global $con;
  $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    return $row['password'];
  }
  return null;
}

function updateUserPassword($user_id, $hashed_password)
{
  global $con;
  $stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("si", $hashed_password, $user_id);
  $stmt->execute();
  return $stmt->affected_rows > 0;
}


function userCanReview($userId, $productId)
{
  global $con;

  // Check if the user has received the product and has not yet left a review
  $sql = "SELECT o.id
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'Livré'
          AND NOT EXISTS (
          SELECT 1 FROM reviews r WHERE r.product_id = oi.product_id AND r.user_id = o.user_id
          )
          LIMIT 1";

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    echo "Error preparing statement: " . $con->error;
    return false;
  }

  $stmt->bind_param("ii", $userId, $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  $canReview = $result->num_rows > 0;

  $stmt->close();
  return $canReview;
}

function getUserByID($user_id)
{
  global $con;

  $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    return $user;
  }
  return null;
}

function updateUserDetailsAdmin($user_id, $first_name, $last_name, $email, $phone_number, $address, $city, $postal_code, $role)
{
  global $con;

  $stmt = $con->prepare("UPDATE users SET firstname = ?,lastname = ?,email = ?, phone_number = ?, address = ?, city = ?, postal_code = ?, role = ? WHERE id = ?");
  $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $phone_number, $address, $city, $postal_code, $role, $user_id);
  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    return false;
  }
  return true;
}


function deleteUserById($id)
{
  global $con;
  $stmt = $con->prepare("DELETE from users where id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  if ($stmt->affected_rows == 0)  return false;
  return true;
}