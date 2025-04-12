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
};
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body style="background-color: #F3F2F7;">


   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Profile</h3>
   <p>Your account information.</p>
</div>
<section class="user-details">
   <div class="user">
         <div class="user-info">
               <p><span>Name : </span><span style="color: grey;"><?= $fetch_profile['name']; ?></span></p>
               <p><span>Number : </span><span style="color: grey;"><?= $fetch_profile['number']; ?></span></p>
               <p><span>Email : </span><span style="color: grey;"><?= $fetch_profile['email']; ?></span></p>
               <p class="address"><i class="fas fa-map-marker-alt"></i><span style="color: grey;">
                  <?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?>
               </span></p>

               <div class="buttons">
                  <a href="update_profile.php" class="sumb">Update info</a>
                  <a href="update_address.php" class="sumb">Update address</a>
               </div>

            </div>
            <div class="user-image">
               <img src="uploaded_img/<?= !empty($fetch_profile['image']) ? $fetch_profile['image'] : 'default-profile.jpg'; ?>" alt="Profile Picture">
            </div>

   </div>
</section>











<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>