<?php
require '../inc/navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = addslashes($_POST['title']);
    $description = addslashes($_POST['description']);
    $stock = addslashes($_POST['stock']);
    $category_id = addslashes($_POST['category_id']);
    $product_id = 3; // Example product ID

    $folder = "../../src/uploads/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $files = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $image_paths = [];

    for ($i = 0; $i < count($files['name']); $i++) {
        if (in_array($files['type'][$i], $allowed) && $files['error'][$i] == 0) {
            $imageName = $files['name'][$i];
            $imagePath = $folder . $imageName;
            if (move_uploaded_file($files['tmp_name'][$i], $imagePath)) {
                $image_paths[] = $imagePath;

                // Insert each image path into the database
                $query = "INSERT INTO images (title, product_id) VALUES ('$imagePath', '$product_id')";
                query($query);
            }
        }
    }

    // Insert product details into the database
    $query = "INSERT INTO products (title, description, stock, category_id) VALUES ('$title', '$description', '$stock', '$category_id')";
    query($query);
}
?>

<form method="post" enctype="multipart/form-data">
  <label for="">Title</label>
  <input type="text" name="title">
  <label for="">Description</label>
  <input type="text" name="description">
  <label for="">Stock</label>
  <input type="text" name="stock">
  <label for="">Category id</label>
  <input type="text" name="category_id">
  <label for="">Images</label>
  <input type="file" name="image[]" class="class_28" multiple>
  <button>
    Submit
  </button>
</form>
</body>

</html>