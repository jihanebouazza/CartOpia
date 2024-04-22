<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // $errors = [];
  $title = addslashes($_POST['title']);
  $description = addslashes($_POST['description']);
  $stock = addslashes($_POST['stock']);
  $category_id = addslashes($_POST['category_id']);

  // if (empty($title)) {
  //   $errors['title'] = "Title is required";
  // } else if (!preg_match("/[a-zA-Z 0-9 \-\_]+$/", trim($title))) {
  //   $errors['title'] = "Title can't have special caracters";
  // }

  $folder = "uploads/";
  if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
  }

  // $image_string = $file_string = "";

  if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (in_array($_FILES['image']['type'], $allowed)) {

      // $image = $folder . $_FILES['image']['name'];
      $image_string = ", image = '$image' ";
    }
  }
  //   } else {
  //     $errors['image'] = "Image type not supported";
  //   }
  // } else {
  //   // if ($mode == 'new')
  //   $errors['image'] = "An image is required";
  // }



  // if (empty($errors)) {

  // if (!empty($image)) {
    move_uploaded_file($_FILES['image']['tmp_name'], $image);


    $query = "insert into songs (user_id,file,image,title,date) values ('$user_id','$file','$image','$title','$date')";


    query($query);
  // }
}

?>

<?php require 'header.php' ?>

<div class="class_22">
  <form method="post" enctype="multipart/form-data" class="class_23">
    <h1 class="class_18">
      <?php if ($mode == 'edit') : ?>
        Edit Song
      <?php elseif ($mode == 'delete') : ?>
        <span>Delete Song</span>
        <div style="color:red;font-size: 18px;">Are you sure you want to delete this song?!</div>
      <?php else : ?>
        Upload Song
      <?php endif; ?>
    </h1>
    <!-- <div style="color: red; padding: 10px;">
      <?php
      if (!empty($errors)) {
        echo implode("<br>", $errors);
      }
      ?>
    </div> -->
    <!-- <?php if ($display) : ?> -->
      <div class="class_24">
        <label class="class_25">
          Title
        </label>
        <input placeholder="" type="text" name="title" class="class_12">
      </div>
      <div class="class_26">
        <img class="class_27 js-image">
        <input onchange="display_image(this.files[0])" type="file" name="image" class="class_28">
      </div>
      <div class="class_26">
        <div class="class_29">
          <audio controls="" class="class_30 js-file">
            <source src="<?= $song['file'] ?? '' ?>" type="audio/mpeg">
          </audio>
        </div>
        <!-- this refers to the input it has a property called files, [0] is to grab the first file selected -->
        <input onchange="load_file(this.files[0])" type="file" name="file">
      </div>
      <div class="class_31">
        <button class="class_32">
          <?= $button_title ?>
        </button>
        <a href="profile.php">
          <button type="button" class="class_33">
            Cancel
          </button>
        </a>
        <div class="class_34">
        </div>
      </div>
    <!-- <?php else : ?>
      <div style="color:red;text-align:center;">That song was not found!</div>
    <?php endif; ?> -->
  </form>
</div>
<!-- <?php require "footer.php" ?> -->

<script>
  function display_image(file) {
    // putting the uploaded image from the input in the src of the image and converting it to base 64
    document.querySelector(".js-image").src = URL.createObjectURL(file);
  }

  function load_file(file) {
    // putting the uploaded image from the input in the src of the image and converting it to base 64
    document.querySelector(".js-file").src = URL.createObjectURL(file);
  }
</script>