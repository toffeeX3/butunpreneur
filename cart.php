<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

// if(isset($_POST['delete'])){
//    $cart_id = $_POST['cart_id'];
//    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
//    $delete_cart_item->execute([$cart_id]);
//    $message[] = 'Cart item deleted';
// }

// if(isset($_POST['delete_all'])){
//    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
//    $delete_cart_item->execute([$user_id]);
//    // header('location:cart.php');
//    $message[] = 'Deleted all from cart';

// }

// if(isset($_POST['update_qty'])){
//    $cart_id = $_POST['cart_id'];
//    $qty = $_POST['qty'];
//    $qty = filter_var($qty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
//    $update_qty->execute([$qty, $cart_id]);
//    $message[] = 'Cart quantity updated';

// }

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

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
   <h3>Your cart</h3>
   <p>Checkout now!</p>
</div>

<!-- shopping cart section starts  -->

<section class="products" style="padding: 7rem;">

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="components/cart_actions_ajax.php" method="post" class="box cart-action-form">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <!-- <button type="submit" class="fas fa-times" name="delete" value="1" onclick="return confirm('delete this item?');"></button> -->
         <button type="submit" name="delete" value="1" class="fas fa-times"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>RP.</span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <!-- <button type="submit" class="fas fa-edit" value="1" name="update_qty"></button> -->
            <button type="submit" name="update_qty" value="1" class="fas fa-edit"></button>

         </div>
         <div class="sub-total"> sub total : <span>RP.<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">your cart is empty</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>Cart total : <span>RP.<?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

   <div class="more-btn">
      <form action="components/cart_actions_ajax.php" method="post" class="delete-all-form">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');">delete all</button>
      </form>
      <a href="menu.php" class="btn">continue shopping</a>
   </div>

</section>

<!-- shopping cart section ends -->










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->








<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>