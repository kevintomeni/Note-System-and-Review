<?php
    include 'connexion.php';

    setcookie('user_id', "", time() - 1, '/');
    header('Location:../all_posts.php');

?>