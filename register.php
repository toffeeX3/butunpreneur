<?php
include 'components/connect.php';

session_start();

$message = []; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

// jika submit di tekan, if ini berlangsung
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // hashhh
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //ngambil email dn number user
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
    $select_user->execute([$email, $number]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Email or number already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Password not matched!';
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $hashed_pass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $hashed_pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if ($select_user->rowCount() > 0) {
                $_SESSION['user_id'] = $row['id'];
                header('location:home.php');
                exit;
            }
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
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="shortcut icon" type="x-icon" href="images/MAINLOGO.png" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #F3F2F7;">
   
<!-- Header Section -->
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>

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
        
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" name="submit" class="btn">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
