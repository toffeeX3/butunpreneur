<?php
include 'components/connect.php';

session_start();

// Initialize the message array
$message = []; 

// Check if user is logged in
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

    if ($row && password_verify($pass, $row['password'])) { 
        $_SESSION['user_id'] = $row['id'];

        header('Location: home.php');
        exit;
    } else {
        $message[] = 'Incorrect email or password!';
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
    <link rel="shortcut icon" type="x-icon" href="images/MAINLOGO.png" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #F3F2F7;">


<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Login Now</h3>

        <!-- Display the messages if there are any -->
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
