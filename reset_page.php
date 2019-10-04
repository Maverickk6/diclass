<?php session_start();

include 'includes/db_connection.php';
include 'includes/functions.php';


if(!isset($_GET['email']) && !(isset($_GET['code']))){
    header("location:index.php");
    exit();
}

$code_unsafe = $_GET['code'];
$email_unsafe = $_GET['email'];

$email = mysqli_real_escape_string($con, $email_unsafe);
$code = mysqli_real_escape_string($con, $code_unsafe);


$now = time();
//$diff = (3600)

$query = mysqli_query($con, "SELECT * FROM password_resets WHERE email = '$email' AND code = '$code' AND ($now - date_added) <= 7200");

$counter = mysqli_num_rows($query);

if($counter == 0){
    addAlert("warning","Invalid password reset code!");
    header("location:login_page.php");
    exit();
}


$row = mysqli_fetch_assoc($query);


if(isset($_POST['ok'])){
    $pass_unsafe = $_POST['password'];
    $password = mysqli_real_escape_string($con, $pass_unsafe);


    if (strlen($password) < 6) {
        addAlert('error', 'Password must be at least Six (6) characters');
        header("location:reset_page.php?code=$code&email=$email");
        exit();
    }

    $type = $row['a_type'];
    if($type == "student") {
        $up = mysqli_query($con,"UPDATE students SET password = '$password' WHERE email = '$email'");
    }elseif ($type == "teacher"){
        $up = mysqli_query($con,"UPDATE teachers SET password = '$password' WHERE email = '$email'");
    }

    $q = mysqli_query($con,"DELETE FROM password_resets WHERE code = '$code' AND email = '$email'");

    addAlert("success","Your password has been changed successfully, kindly login to your account!");
    header("location:login_page.php");
    exit();
}

//var_dump($row);

//exit();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Reset Password -DiClass</title>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
</head>
<body>
    <div class="signup-container signup_login">
        <header>
            <a href="index.php"><img src="assets/logo.png" alt="Team Dinlas"></a>
            <h1>Reset Password</h1>
            <p>New to DiClass? <a href="signup_page.php">Sign Up</a></p>
            <br>
            <!-- we display proper error or success messages -->
            <?php 

            echo showAlert(); ?>
        </header>
        <form action="" method="POST" class="login-form">
           
            <label for="email">
                <input type="email" name="email" id="email" placeholder="example@gmail.com" value="<?php echo $row['email'];?>">
            </label>

            <label for="password">
                Enter new password
                <input type="password" name="password" id="password" placeholder="Password">
            </label>

            <label for="type">
                <select name="type" required disabled readonly="">
                    <option value="student">Student</option>
                    <option value="teacher" <?php if($row['a_type'] == "teacher") echo "selected";?>>Teacher</option>
                </select>
            </label>
    
            <button type="submit" name="ok" >Change Password</button>

        </form>

    </div>


<!--sign up modal-->


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script> <!--Jquery included-->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script> <!--bootstrap js included-->
</body>
</html>