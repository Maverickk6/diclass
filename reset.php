<?php session_start();


//reset password script created by @Hollyphat
//Proper Database configuration here
include 'includes/db_connection.php';
include 'includes/functions.php';


/*EMAIL SENDING SCRIPT*/

function send_email($subject,$to,$message,$cc = FALSE)
{
    $email_tmp = file_get_contents("email.html");
    //$message2 = str_replace("{{TITLE}}", $subject, $email_tmp);

    $message = str_replace("EMAIL_AREA", $message, $email_tmp);

    //str_replace(search, replace, subject)

    $full_name = "Di-Class";
    $email_from = "no-reply@di-class.herokuapp.com";

    $from = "$full_name <$email_from>";
    $headers = 'From:'.$full_name.'<'.$email_from.'>'."\r\n";
    if($cc != FALSE)
    {
        if(is_array($cc)) {
            $headers .= 'BCC: ' . implode(",", $cc) . "\r\n";
        }else{
            $headers .= 'BCC: ' . $cc . "\r\n";
        }

    }
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


    //echo $message;
    //exit();

    @mail($to, $subject, $message, $headers);
}



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
            $code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).$id;

            $date_added = time();
            //insert code
            $insert = mysqli_query($con, "INSERT INTO password_reset(user_id, email, code, date_added) VALUES ('$id','$email','$code','$date_added')");


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
