<header class="header">
    <div class="flex">
        <a href="all_posts.php" class="logo"><img src="image/11.jpg" width="100"></a>
        <nav class="navbar">
            <a href="add_products.php" class="far fa-plus"></a>
            <a href="all_posts.php" class="far fa-eye"></a>
            <a href="login.php" class="fas fa-arrow-right-to-bracket"></a>
            <a href="register.php" class="far fa-registered"></a>
            <div id="user-btn" class="far fa-user"></div>
        </nav>
        <div class="profile">
            <?php 
                $select_profile = $conn ->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
                $select_profile->execute(['user_id']);
                if($select_profile->rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                
            ?>
            <?php
            if ($fetch_profile['image'] != '') { ?>
               <img src="uploaded_file/<?= $fetch_profile['image']; ?>" class="image">
          <?php  }?>
          <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <a href="update.php" class="btn">update profile</a>
                <a href="components/logout.php" class="delete-btn" onclick="return confirm('logout from this website');">logout</a>
            </div>
            <?php }else {?>
               <img src="image/user.jpg" class="image">
               <p class="name">please login or register first!</p>
               <div class="flex-btn">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="delete-btn">register</a>
               </div>
         <?php   }?>
        </div>
    </div>
</header>
<div class="banner">
</div>