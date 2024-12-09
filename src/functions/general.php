<?php
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

function redirect($page)
{
  header("Location: " . ROOT . "/$page.php");
  die;
}