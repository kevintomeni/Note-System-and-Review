<?php
include 'components/connexion.php';

if (isset($_POST['submit'])) {
    $select_user = $conn->prepare("SELECT `users` WHERE id = ? LIMIT 1");
    $select_user->execute([$user_id]);

    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    //update name
    if (!empty($name)) {
       $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ? ");
       $update_name->execute([$name, $user_id]);
       $success_msg[] = "User name updated";
    }

    //update email
    if (!empty($email)) {
       $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email=?");
       $verify_email->execute([$email]);
       if ($verify_email->rowCount() > 0 ) {
        $warning_msg[]='Email already taken';
       }else{
        $update_email = $conn->prepare("UPDATE `users` SET email = ?");
        $update_email->execute([$email, $user_id]);
        $success_msg[] = "User name updated";
        }
    }

   //update image
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id().'.'.$ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_file/'.$rename;

    if (!empty($image)) {
        if ($image_size > 2000000) {
        $warning_msg[]="Image size is too large";
            }else{
                $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $user_id]);
                move_uploaded_file($image_tmp_name, $image_folder);
                if ($fetch_user['image'] != '') {
                    unlink('uploaded_file/'.$fetch_user['image']);
                }
                $success_msg[]='Profile image was successfully updated';
        }
    }
    
    $prev_pass = $fetch_user['password'];

    $old_pass = password_hash($_POST['old_pass'], PASSWORD_DEFAULT);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

    $empty_old = password_verify('', $old_pass);

    $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

    $empty_new = password_verify('', $new_pass);

    $c_pass = password_verify($_POST['c_pass'], $prev_pass);
    $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

    if ($empty_old != 1) {
        $verify_old_pass = password_verify($_POST['old_pass'], $prev_pass);
        if ($verify_old_pass == 1) {
            if ($c_pass == 1) {
                if ($empty_new != 1) {
                    $update_pass = $conn->prepare('UPDATE `users` SET password = ? WHERE id = ?');
                    $success_msg[]= "Password updated successfully";
                }else{
                    $warning_msg[] = "Please enter a new password";
                }
            }else{
                $warning_msg[] = "Confirm password not matched";
            }
        }else{
            $warning_msg[] = "old password not matched";
        }
    }
}

//delete profile pic

if (isset($_POST['delete_image'])) {
$select_old_pic = $conn->prepare('SELECT * FROM `users` WHERE id = ? LIMIT 1');
$select_old_pic->execute([$user_id]);
$fetch_old_pic = $select_old_pic->fetch(PDO::FETCH_ASSOC);

if ($fetch_old_pic['image'] == '') {
    $warning_msg[] = "Image already deleted";
} else {
    $update_old_pic = $conn->prepare('UPDATE `users` SET image = ? WHERE id = ?');
    $update_old_pic->execute(['', $user_id]);
    if ($fetch_old_pic != '') {
        unlink('uploaded_files/'.$fetch_old_pic['image']);
    }
    $success_msg[] = "Image deleted successfully";
}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update page</title>
</head>
<body>
    <?php include 'components/header.php';?>


    <section class="account-form">
        <h3 class="heading">make your account</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <p class="placeholder">your name <span>*</span></p>
                <input type="text" name="name"  maxlength="50" placeholder="<?= $fetch_profile['name']; ?>" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">your email <span>*</span></p>
                <input type="email" name="email"  maxlength="50" placeholder="<?= $fetch_profile['email']; ?>" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">old password <span>*</span></p>
                <input type="password" name="old_pass"  maxlength="50" placeholder="Enter your old password" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">new password <span>*</span></p>
                <input type="password" name="new_pass"  maxlength="50" placeholder="Enter your new password" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Confirm password <span>*</span></p>
                <input type="password" name="cpass"  maxlength="50" placeholder="Confirm your password" class="box">
            </div>
            <?php if($fetch_profile['image'] != '') {?>
                <img src="uploaded_file/<?= $fetch_profile['image']; ?>" class="image">
                <input type="submit" name="delete_image" class="delete-btn" onclick="return confirm('delete this image');" value="delete profile">
                <?php } ?>
            <div class="input-field">
                <p class="placeholder"> profile pic <span>*</span></p>
                <input type="file" name="image" accept="image/*" class="box">
            </div>
            <input type="submit" value="update profile" class="btn" name="submit">
        </form>
    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>