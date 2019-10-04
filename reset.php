<?php session_start();


//reset password script created by @Hollyphat
//Proper Database configuration here
include 'includes/db_connection.php';
include 'includes/functions.php';

/*
Here we perform the logic for database
 */

if (isset($_POST['reset'])) {

    $email_unsafe = $_POST['email'];


    $email = mysqli_real_escape_string($con, $email_unsafe);

    if($_POST['type'] == 'student'){

        $query = mysqli_query($con, "SELECT id,name FROM students WHERE email = '$email'") or die(mysqli_error($con));
        $counter = mysqli_num_rows($query);

        if ($counter == 0) {
            addAlert('error', 'User account not found');
            header("location:login_page.php");
        } else {

            $row = mysqli_fetch_array($query);
            $name = $row['fullname'];
            $id = $row['student_id'];

            //generate random code



            addAlert('success', 'A password reset link has been sent to your email!');
            header("location:login_page.php");
        }


    }else if($_POST['type'] == 'teacher'){

        $query = mysqli_query($con, "SELECT * FROM teachers WHERE email = '$email' AND password = '$password'") or die(mysqli_error($con));
$counter = mysqli_num_rows($query);

if ($counter == 0) {

    addAlert('error', 'Invalid Email or Password! Try again');
    echo "<script>document.location='login_page.php'</script>";
} else {

    //Get user details from db
    $row = mysqli_fetch_array($query);
    $name = $row['fullname'];
    $id = $row['teacher_id'];

    //Add to Session
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $name;
    $_SESSION['type'] = 'teacher';
    addAlert('success', 'You Successfully Logged in');
    echo "<script type='text/javascript'>document.location='teacher_dashboard.php'</script>";
}


    }

   
} else {
    header('Location index.php');
}
