<?php

if (isset($_SESSION['message'])) {
   echo '<div class="message-box" id="message-box">';
   echo $_SESSION['message']; 
   echo '<button id="close-message">Close</button>';
   echo '</div>';
   unset($_SESSION['message']);
}


if(isset($_POST['add_to_cart'])){

   if($user_id == '') {
      header('location:login.php');
   } else {

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0) {
         // $_SESSION['message'] = 'Already added to cart!';         
         $message[] = 'Already added to cart!';
      } else {
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         // $_SESSION['message'] = 'Added to cart!';
         $message[] = 'Added to cart!';
      }

      // Redirect back to the current page where the action was triggered
      // header('Location: ' . $_SERVER['REQUEST_URI']);
      // exit;   
   }
}

?>