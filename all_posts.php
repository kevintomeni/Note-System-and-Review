<?php
include 'components/connexion.php';
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
    <title>all post</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <section class="all_posts">
        <h3 class="heading">all posts</h3>
        <div class="box-container">
            <?php
            $select_posts = $conn->prepare("SELECT * FROM products");
            $select_posts->execute();
            if ($select_posts->rowCount() > 0) {
                while ($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                 $post_id = $fetch_post['id'];
                 $count_reviews = $conn->prepare("SELECT * FROM reviews WHERE post_id = ?");
                 $count_reviews->execute([$post_id]);
                 $total_reviews = $count_reviews->rowCount();
            ?>
            <div class="box">
            <img src="uploaded_file/<?= $fetch_post['image']; ?>" class="image">
            <h3 class="title"><?= $fetch_post['name']; ?></h3>
                <p class="total-reviews"><i class="fas fa-star"></i><span><?= $total_reviews; ?></span></p>
                <a href="view_post.php?get_id=<?= $post_id; ?>" class="btn">view post</a>
            </div>
            <?php
                 }
                }else{
                    echo '<p class="empty">no post added yet!</p>';
                }
             ?>
        </div>
    </section>
    <script type="text/javascript" src="js/app.js"></script>
</body>
</html>