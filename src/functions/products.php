<?php

function uploadImage(){
  $folder = "uploads/";
  if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
  }

  // $image_string = $file_string = "";

  if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (in_array($_FILES['image']['type'], $allowed)) {

      $image = $folder . $_FILES['image']['name'];
      // $image_string = ", image = '$image' ";
    } else {
      $errors['image'] = "Image type not supported";
    }
  } else {
    // if ($mode == 'new')
      $errors['image'] = "An image is required";
  }
}