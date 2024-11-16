<?php
include 'components/connexion.php';

if (isset($_POST['submit'])) {
   
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $verify_email->execute([$email]);

    if ($verify_email->rowCount() > 0) {
      $fetch = $verify_email->fetch(PDO::FETCH_ASSOC);
      $verify_pass = password_verify($pass, $fetch['password']);
      if ($verify_pass == 1) {
        setcookie('user_id', $fetch['id'], time() + 60*60*24*30, '/');
        header('location:all_posts.php');
      }else{
        $warning_msg[] = "Incorrect password!";
      }
    }else{
        $warning_msg[] = "Incorrect email!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login page</title>
</head>
<body>
    <?php include 'components/header.php';?>


    <section class="account-form">
        <h3 class="heading">make your account</h3>
        <form action="" method="post" enctype="multipart/form-data">
           
            <div class="input-field">
                <p class="placeholder">your email <span>*</span></p>
                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">your password <span>*</span></p>
                <input type="password" name="pass" required maxlength="50" placeholder="Enter your password" class="box">
            </div>
            
            <input type="submit" value="Login now" class="btn" name="submit">
            <p class="link">do not have an account ? <a href="register.php">register now</a></p>
        </form>
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>