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