<?php
include 'components/connexion.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
}else {
    $get_id = '';
    header('location:all_posts.php');
}

if (isset($_POST['submit'])) {
    if ($user_id != '') {
        $id = create_unique_id();
        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);

        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $rating = $_POST['rating'];
        $rating = filter_var($rating, FILTER_SANITIZE_STRING);

        $verify_rating = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND user_id = ?");
        $verify_rating->execute([$get_id, $user_id]);

        if ($verify_rating->rowCount() > 0) {
            $warning_msg[]= "Your review already added";
        }else{
            $add_review = $conn->prepare("INSERT INTO `reviews` (id, post_id, user_id, rating, title, description) VALUES (?,?,?,?,?,?)");
            $add_review->execute([$id, $get_id, $user_id, $rating, $title, $description]);
            $success_msg[] = "Review added successfully";
        }
    }else{
        $warning_msg[]= "please login first";
    }
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
        <form action="" method="post" enctype="multipart/form-data">
           
            <div class="input-field">
                <p class="placeholder">review title <span>*</span></p>
                <input type="text" name="title" required maxlength="50" placeholder="Enter review title" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">review description <span>*</span></p>
                <textarea name="description" class="box"  placeholder="Enter review description" maxlength="100" cols="30" rows="10" ></textarea>
            </div>
            <div class="input-field">
               <select name="rating" class="box" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
               </select>
            </div>
            <div class="flex-btn">
            <input type="submit" value="submit review" class="btn" name="submit" style="width: 50%;">
            <a href="view_post.php?get_id=<?= $get_id ?>" class="delete-btn" style="width: 30%;">go back</a>
            </div>
        </form>
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>