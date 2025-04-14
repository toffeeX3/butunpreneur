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
   exit;
}


//track buat msg
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

// nyimpen
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== end($_SESSION['history'])) {
    $_SESSION['history'][] = $_SERVER['HTTP_REFERER'];
}

if (count($_SESSION['history']) > 3) {
    array_shift($_SESSION['history']); 
}

if(isset($_POST['submit'])){

   $address = $_POST['angkatan'] .', '.$_POST['jurusan'].', '.$_POST['kelas'].', '.$_POST['gedung'];
   $address = filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $_SESSION['message'] = 'Address saved successfully!';

   $second_previous_page = $_SESSION['history'][count($_SESSION['history']) - 2] ?? 'menu.php';

   header("Location: profile.php");
   exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Address</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="shortcut icon" type="x-icon" href="images/MAINLOGO.png" />

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body style="background-color: #F3F2F7;">
   
<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Your Address</h3>
      <input type="text" class="box" placeholder="Angkatan" required maxlength="50" name="angkatan">
      <input type="text" class="box" placeholder="Jurusan" required maxlength="50" name="jurusan">
      <input type="text" class="box" placeholder="Kelas" required maxlength="50" name="kelas">
      <input type="text" class="box" placeholder="Gedung" required maxlength="50" name="gedung">
      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>

</section>

<?php include 'components/footer.php' ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
