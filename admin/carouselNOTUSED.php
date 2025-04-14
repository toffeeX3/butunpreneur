<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add'])){

   $cid = $_POST['cid'];
   $cid = filter_var($cid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $text = $_POST['text'];
   $text = filter_var($text, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $caption = $_POST['caption'];
   $caption = filter_var($caption, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $update_carousel = $conn->prepare("UPDATE `carousel` SET text = ?, caption = ?, WHERE id = ?");
   $update_carousel->execute([$text, $caption, $cid]);

   $message[] = 'carousel updated!';

   $old_img = $_POST['old_img'];
   $img = $_FILES['image']['name'];
   $img = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $img = $_FILES['image']['size'];
   $img_tmp_name = $_FILES['image']['tmp_name'];
   $img_folder = '../uploaded_img/'.$img;

   if(!empty($img)){
      if($img_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         $update_img = $conn->prepare("UPDATE `carousel` SET img = ? WHERE id = ?");
         $update_img->execute([$img, $cid]);
         move_uploaded_file($img_tmp_name, $img_folder);
         unlink('../uploaded_img/'.$old_img);
         $message[] = 'image updated!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">update product</h1>

   <?php
      $add_id = $_GET['add'];
      $show_carousel = $conn->prepare("SELECT * FROM `carousel` WHERE id = ?");
      $show_carousel->execute([$add_id]);
      if($show_carousel->rowCount() > 0){
         while($fetch_carousel = $show_carousel->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="cid" value="<?= $fetch_carousel['id']; ?>">
      <input type="hidden" name="old_img" value="<?= $fetch_carousel['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_carousel['image']; ?>" alt="">
      <span>update name</span>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_carousel['name']; ?>">
      <span>update price</span>
      <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_carousel['price']; ?>">
      <span>update category</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_carousel['category']; ?>"><?= $fetch_carousel['category']; ?></option>
         <option value="main dish">main dish</option>
         <option value="fast food">fast food</option>
         <option value="drinks">drinks</option>
         <option value="desserts">desserts</option>
      </select>
      <span>update image</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="update" class="btn" name="update">
         <a href="products.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

</section>

<!-- update product section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>