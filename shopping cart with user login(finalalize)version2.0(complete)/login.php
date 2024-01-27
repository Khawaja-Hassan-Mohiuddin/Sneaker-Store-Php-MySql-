<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {

    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);

    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE User_Email = '$user_email'") or die('query failed');

    $messages = [];
    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $hashed_password = $row['User_Password'];

        // Verify the entered password with the hashed password in the database
        if (password_verify($user_password, $hashed_password)) {
            $_SESSION['user_id'] = $row['User_Id'];
            header('location:index.php');
        } else {
            $message[] = 'Incorrect password!';
        }
    } else {
        $message[] = 'Email not found!';
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

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>

    <div class="form-container">

        <form action="" method="post">
            <h3>Login Now</h3>
            <input type="email" name="user_email" required placeholder="Enter Email" class="box">
            <input type="password" name="user_password" required placeholder="Enter Password" class="box">
            <input type="submit" name="submit" class="btn" value="Login Now">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>

    </div>

</body>

</html>
