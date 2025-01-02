<?php
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
  if (is_logged_in()) {
    if ($_SESSION['USER']['role'] == 'admin')
      return true;
  }
  return false;
}

function login($email)
{
  global $con;
  $stmt = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
  if ($stmt === false) {
    // Output error if query preparation failed
    die('MySQL prepare error: ' . $con->error);
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    return  $result->fetch_assoc();
  }
  return false;
}

function emailExists($email)
{
  global $con;
  $count = 0;
  $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();
  return $count;
}

function signup($first_name, $last_name, $email, $hashed_password) {
  global $con;
  $stmt = $con->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
  $stmt->execute();

  if ($stmt->affected_rows === 1) {
    return $stmt->insert_id;
  }
  return false;
}
