<?php

include 'config.php';

if (isset($_POST['submit'])) {

    $username = mysqli_real_escape_string($conn, $_POST['User_Name']);
    // print_r($username);
    $email = mysqli_real_escape_string($conn, $_POST['User_Email']);
    $password = mysqli_real_escape_string($conn, $_POST['User_Password']);
    $passwordRepeat = mysqli_real_escape_string($conn, $_POST['Repeat_Password']);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();

    if (empty($username) || empty($email) ) {
        $errors[] = "Username and Email are required";
    }
    else{
        if(empty($password) || empty($passwordRepeat)){
            $errors[] = "Password and Confirm Password are required";
        }

    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    if ($password !== $passwordRepeat) {
        $errors[] = "Password does not match";
    }

    $sql = "SELECT * FROM `users` WHERE User_Email='$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        $errors[] = "Email already exists!";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO `users`(User_Name, User_Email, User_Password) VALUES(?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            $errors[] = "Registered Successfully";
            header('location:Login.php');
        }else{
            $errors[]="Error preparing statement: ".mysqli_error($conn);
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

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="message" >' . $error . '</div>';
        }
    }
    ?>

    <div class="form-container">

        <form action="Register.php" method="post">
            <h3>Register Now</h3>
            <input type="text" name="User_Name" placeholder="Enter Username" class="box">
            <input type="email" name="User_Email" placeholder="Enter Email" class="box">
            <input type="password" name="User_Password" placeholder="Enter Password" class="box">
            <input type="password" name="Repeat_Password" placeholder="Confirm Password" class="box">
            <input type="submit" name="submit" class="btn" value="Register now">
            <p>Already Have An Account? <a href="Login.php">Login Now</a></p>
        </form>

    </div>

</body>

</html>
