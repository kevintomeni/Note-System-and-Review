<?php
include 'components/connexion.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
}else {
    $get_id = '';
    header('location:all_posts.php');
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);

    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);

    $rating = $_POST['rating'];
    $rating = filter_var($rating, FILTER_SANITIZE_STRING);

    $update_review = $conn->prepare("UPDATE `reviews` SET rating =?, title =?, description =? WHERE id = ?");
    $update_review->execute([$rating, $title, $description, $get_id]);
    $success_msg[] ="Rating updated successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>add your review page</title>
</head>
<body>
    <?php include 'components/header.php';?>


    <section class="account-form">
        <h3 class="heading">post your review</h3>
        <?php
        $select_review = $conn->prepare("SELECT * FROM `reviews` WHERE id = ? LIMIT 1");
        $select_review->execute([$get_id]);
        if ($select_review->rowCount() > 0) {
            while ($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)) {
                
            
         ?>
        <form action="" method="post" enctype="multipart/form-data">
           
            <div class="input-field">
                <p class="placeholder">review title <span>*</span></p>
                <input type="text" name="title" required maxlength="50" placeholder="Enter review title" class="box" value="<?= $fetch_review['title'];  ?>">
            </div>
            <div class="input-field">
                <p class="placeholder">review description <span>*</span></p>
                <textarea name="description" class="box"  placeholder="Enter review description" maxlength="100" cols="30" rows="10" ><?= $fetch_review['description'];  ?></textarea>
            </div>
            <div class="input-field">
               <select name="rating" class="box" required>
                <option value="<?= $fetch_review['rating'];  ?>"> <?= $fetch_review['rating'];  ?></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
               </select>
            </div>
            <div class="flex-btn">
            <input type="submit" value="update review" class="btn" name="submit" style="width: 50%;">
            <a href="view_post.php?get_id=<?= $fetch_review['post_id']; ?>" class="delete-btn" style="width: 30%;">go back</a>
            </div>
        </form>
        <?php 
             } 
        }else{
            echo '<p class="empty">something went wrong</p>';
        }
        ?>
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>