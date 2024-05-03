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
