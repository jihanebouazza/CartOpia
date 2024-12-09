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



