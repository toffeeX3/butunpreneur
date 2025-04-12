<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['message'])) {
   echo '<div class="message-box" id="message-box">';
   echo $_SESSION['message']; 
   echo '<button id="close-message">Close</button>';
   echo '</div>';
   unset($_SESSION['message']);
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
}

$success = true;

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
      if(!$update_name->execute([$name, $user_id])){
         $success = false;
         $_SESSION['message'] = 'Failed to update name!';
         header('Location: update_profile.php');
         exit;
      } else {
         $_SESSION['message'] = 'Name successfully updated!';
      }
      header('Location: profile.php');
         exit;
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $success = false;
         $_SESSION['message'] = 'Email is already taken!';
         header('Location: update_profile.php');
         exit;
      }else{
         $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
         if(!$update_email->execute([$email, $user_id])){
            $success = false;
            $_SESSION['message'] = 'Failed to update email!';
            header('Location: update_profile.php');
            exit;
         } else {
            $_SESSION['message'] = 'Email saved successfully!';
         }
         header('Location: profile.php');
         exit;
      }
   }

   if(!empty($number)){
      $select_number = $conn->prepare("SELECT * FROM users WHERE number = ?");
      $select_number->execute([$number]);
      if($select_number->rowCount() > 0){
         $success = false;
         $_SESSION['message'] = 'Number already taken!';
         header('Location: update_profile.php');
         exit;
      }else{
         $update_number = $conn->prepare("UPDATE users SET number = ? WHERE id = ?");
         if(!$update_number->execute([$number, $user_id])){
            $success = false;
            $_SESSION['message'] = 'Failed to update number!';
            header('Location: update_profile.php');
            exit;
         } else {
            $_SESSION['message'] = 'Number successfully updated!';
            header('Location: profile.php');
            exit;
         }
      }
   }
   
   $empty_pass = '';
   $select_prev_pass = $conn->prepare("SELECT password FROM users WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = filter_var($_POST['old_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $new_pass = filter_var($_POST['new_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $confirm_pass = filter_var($_POST['confirm_pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $success = false;
         $_SESSION['message'] = 'Old password not matched!';
         header('Location: update_profile.php');
         exit;
      }elseif($new_pass != $confirm_pass){
         $success = false;
         $_SESSION['message'] = 'Confirm password not matched!';
         header('Location: update_profile.php');
         exit;
      }else{
         $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
         if(!$update_pass->execute([$confirm_pass, $user_id])){
            $success = false;
            $_SESSION['message'] = 'Failed to update password!';
            header('Location: update_profile.php');
            exit;
         } else {
            $_SESSION['message'] = 'Password updated successfully!';
         }
         header('Location: profile.php');
            exit;
      }
   }

   if (isset($_FILES['uploaded_img']) && $_FILES['uploaded_img']['error'] == 0) {
      $target_dir = "uploaded_img/";
      $file_name = basename($_FILES["uploaded_img"]["name"]);
      $target_path = $target_dir . $file_name;
      $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
   
      $check = getimagesize($_FILES["uploaded_img"]["tmp_name"]);
      if ($check !== false) {
         if ($_FILES["uploaded_img"]["size"] <= 5000000) {
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
               if (move_uploaded_file($_FILES["uploaded_img"]["tmp_name"], $target_path)) {
                  // 🔥 ONLY store filename
                  $update_image = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
                  if ($update_image->execute([$file_name, $user_id])) {
                     $_SESSION['message'] = 'Profile picture updated successfully!';
                  } else {
                     $success = false;
                     $_SESSION['message'] = 'Failed to update profile picture!';
                  }
               } else {
                  $success = false;
                  $_SESSION['message'] = 'Failed to move uploaded file!';
               }
            } else {
               $success = false;
               $_SESSION['message'] = 'Only JPG, JPEG, PNG, and GIF files are allowed!';
            }
         } else {
            $success = false;
            $_SESSION['message'] = 'File size must be less than 5MB!';
         }
      } else {
         $success = false;
         $_SESSION['message'] = 'File is not a valid image!';
      }
   }
   

     if ($success) {
      header('Location: profile.php');
      exit;
   } else {
      header('Location: update_profile.php');
      exit;
   }

   // if($success){
   //    header('Location: profile.php');
   //    exit;
   // }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body style="background-color: #F3F2F7;">
   
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container update-form">

<form action="" method="post" enctype="multipart/form-data">
   <h3>update profile</h3>
   <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
   <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="number" name="number" placeholder="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="10">
   <input type="password" name="old_pass" placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="new_pass" placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="confirm_pass" placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="file" name="uploaded_img" accept="image/*" class="box">

   <input type="submit" value="update now" name="submit" class="btn">
</form>

</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>