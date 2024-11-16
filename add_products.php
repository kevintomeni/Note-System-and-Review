<?php
include 'components/connexion.php';

if (isset($_POST['add_product'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);

    $stock = password_hash( $_POST['stock'], PASSWORD_DEFAULT);
    $stock = filter_var($stock, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id().'.'.$ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_file/'.$rename;

    if ($image_size > 2000000) {
        $warning_msg ="Image size is too large";
    } else {
        $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, stock, image) VALUES(?, ?, ?, ?, ?)");
        $insert_product->execute([$id, $name, $price, $stock, $rename]);
        move_uploaded_file ($image_tmp_name, $image_folder);
        $success_msg[]="Product Added Successfully";
    }
    
}
?>
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Add producs page</title>
</head>
<body>
    <?php include 'components/header.php';?>


    <section class="add-product">
      <h1 class="heading">add product</h1>
      <form action="" method="post" enctype="multipart/form-data">
        <h3>produtc details</h3>
        <div class="input-field">
            <p>product name <span>*</span></p>
            <input type="text" name="name" required maxlength="50" placeholder="Enter product name" class="box">
        </div>
        <div class="input-field">
            <p>product price <span>*</span></p>
            <input type="number" name="price" required maxlength="10" placeholder="Enter product price" min='0' max='999999999' class="box">
        </div>
        <div class="input-field">
            <p>total stock<span>*</span></p>
            <input type="number" name="stock" required maxlength="10" placeholder="total stock available" min='0' max='999999999' class="box">
        </div>
        <div class="input-field">
            <p>product image<span>*</span></p>
            <input type="file" name="image" required accept='image/*' class="input">
        </div>
        <input type="submit" name ="add_product" value="add product" class="btn">
      </form>
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>