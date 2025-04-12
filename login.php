<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['message'])) {
    echo '<div class="message-box" id="message-box">'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
 }

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Select user by email
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Check if password matches the hashed password in the database
    if ($row && password_verify($pass, $row['password'])) { 
        $_SESSION['user_id'] = $row['id'];
        header('location:home.php');
        exit;
    } else {
        $_SESSION['message'] = 'Incorrect username or password!';
        header('Location: login.php');
        exit;

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #F3F2F7;">
<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Login Now</h3>
        <?php if (isset($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Login Now" name="submit" class="btn">
        <p>Don't have an account? <a href="register.php">Register now</a></p>
    </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
