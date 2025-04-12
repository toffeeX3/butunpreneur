<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body style="background-color: #F3F2F7;">

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div id="message-box" class="message-box">
                '.$msg.'
                <button id="close-message">Close</button>
              </div>';
    }
}
?>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
<form action="components/add_cart_ajax.php" method="post" class="box add-to-cart-form">
   <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="poster" value="<?= $fetch_products['poster']; ?>">
      <input type="hidden" name="poster_no" value="<?= $fetch_products['poster_no']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div style="padding-top: 1rem; margin-right: auto; font-size: 2rem; color:var(--light-color);">Seller name: <?= $fetch_products['poster']; ?></div>
      <div style="padding-top: 1rem; margin-right: auto; font-size: 2rem; color:var(--light-color); padding-bottom:5rem;">Seller contact: +<a href="https://wa.me/<?= $fetch_products['poster_no']; ?>?text=I'm%20interested%20in%20your <?= $fetch_products['name']; ?>" target="_blank"> <?= $fetch_products['poster_no']; ?> </a></div>
      <div class="flex">
         <div class="price"><span>RP.</span><?= $fetch_products['price']; ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
      </div>
      
      <button type="submit" name="add_to_cart" class="cart-btn">add to cart</button>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

</section>
















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>