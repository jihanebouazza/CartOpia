<?php
function redirect($page)
{
  header("Location: " . ROOT . "/$page.php");
  die;
}

function auth($row)
{
  $_SESSION['USER'] = $row;
}

function user($key)
{
  if (!empty($_SESSION['USER'][$key]))
    return $_SESSION['USER'][$key];

  return "";
}

function is_logged_in(): bool
{
  if (!empty($_SESSION['USER']) && is_array($_SESSION['USER'])) {
    return true;
  }

  return false;
}

function is_admin(): bool
{
  if (!empty($_SESSION['USER']) && is_array($_SESSION['USER'])) {
    if ($_SESSION['USER']['role'] == 'admin')
      return true;
  }

  return false;
}

function updateUserDetails($user_id, $phone_number, $address, $city, $postal_code)
{
  global $con; // Database connection variable

  $stmt = $con->prepare("UPDATE users SET phone_number = ?, address = ?, city = ?, postal_code = ? WHERE id = ?");
  $stmt->bind_param("ssssi", $phone_number, $address, $city, $postal_code, $user_id);
  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    return false; // No rows updated
  }
  return true; // Details updated successfully
}

function updateUserProfile($user_id, $first_name, $last_name, $email)
{
  global $con; // Ensure you have a global database connection variable

  $stmt = $con->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
  $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    // Update session if it's the same user
    if ($_SESSION['USER']['id'] == $user_id) {
      $_SESSION['USER']['firstname'] = $first_name;
      $_SESSION['USER']['lastname'] = $last_name;
      $_SESSION['USER']['email'] = $email;
    }
    return true; // User details updated successfully
  }
  return false; // No details were updated
}

function getUserPassword($user_id)
{
  global $con; // Database connection variable
  $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    return $row['password'];
  }
  return null; // Return null if no user found
}


function updateUserPassword($user_id, $hashed_password)
{
  global $con;
  $stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("si", $hashed_password, $user_id);
  $stmt->execute();
  return $stmt->affected_rows > 0; // Returns true if the password was successfully updated
}


// function userCanReview($userId, $productId)
// {
//   global $con; // Assuming $con is your database connection

//   $sql = "SELECT o.id
//           FROM orders o
//           JOIN order_items oi ON o.id = oi.order_id
//           WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'Livré'
//           LIMIT 1";

//   $stmt = $con->prepare($sql);
//   if (!$stmt) {
//     echo "Error preparing statement: " . $con->error;
//     return false;
//   }

//   $stmt->bind_param("ii", $userId, $productId);
//   $stmt->execute();
//   $result = $stmt->get_result();

//   $canReview = $result->num_rows > 0;

//   $stmt->close();
//   return $canReview;
// }

function userCanReview($userId, $productId)
{
  global $con; // Assuming $con is your database connection

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



// function getUserByID($user_id)
// {
//   global $con; // Ensure you have a global database connection variable

//   $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
//   $stmt->bind_param("i", $user_id);
//   $stmt->execute();
//   $result = $stmt->get_result();

//   if ($user = $result->fetch_assoc()) {
//     return $user; // Return user details as an associative array
//   }
//   return null; // Return null if no user found
// }
