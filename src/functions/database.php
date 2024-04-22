<?php
session_start();

function query($query)
{
  // refers to the con variable in the connection.php file
  global $con;

  $result = mysqli_query($con, $query); // either returns false or the data
  if (!is_bool($result)) { // if result is false $result will return false so if the inner condition executes it will cause an error mysqli_num_rows(false)
    if (mysqli_num_rows($result) > 0) {
      $rows = [];
      while ($row = mysqli_fetch_assoc($result)) { //fetching an associative array
        $rows[] = $row;
      }
      return $rows;
    }
  }
  return false;
}
