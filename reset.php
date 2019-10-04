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

        $query = mysqli_query($con, "SELECT student_id,fullname FROM students WHERE email = '$email'") or die(mysqli_error($con));
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
            $insert = mysqli_query($con, "INSERT INTO password_resets(user_id, email, code, date_added, a_type) VALUES ('$id','$email','$code','$date_added','student')");

            //send the email

            $url = "$baseUrl/reset_page.php?email=$email&code=$code";
            $link = "<a href=\"$url\" style=\"-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #052d3d; background-color: #42a8de; border-radius: 14px; -webkit-border-radius: 14px; -moz-border-radius: 14px; width: auto; width: auto; border-top: 1px solid #42a8de; border-right: 1px solid #42a8de; border-bottom: 1px solid #42a8de; border-left: 1px solid #42a8de; padding-top: 5px; padding-bottom: 5px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;\" target=\"_blank\"><span style=\"padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;\">
<span style=\"font-size: 16px; line-height: 2; mso-line-height-alt: 32px;\"><span style=\"font-size: 14px; line-height: 28px; color: #fff !important\">Click Here to Reset Password</span></span>
</span></a>";

            $subject = "Di-Class Password Reset";
            $message = "<p>Hi $name, you recently requested to change your Di-Class student account password</p>";
            $message .= "<p>Kindly click on the link below to reset your password!</p>";
            $message .= "<p align='center'>$link</p>";
            $message .= "<p>Alternatively, you can also copy and paste the link below in your web browser!</p>";
            $message .= "<p align='center'>$url</p>";

            send_email($subject,"$email","$message");

            //exit();

            addAlert('success', 'A password reset link has been sent to your email!<br>Note that the link expires in one hour!');
            header("location:login_page.php");
            exit();
        }


    }else if($_POST['type'] == 'teacher'){

        $query = mysqli_query($con, "SELECT fullname,teacher_id FROM teachers WHERE email = '$email'") or die(mysqli_error($con));
$counter = mysqli_num_rows($query);

if ($counter == 0) {

    addAlert('error', 'Invalid Email Address');
    header("location:login.php");
    exit();
} else {

    //Get user details from db
    $row = mysqli_fetch_array($query);
    $name = $row['fullname'];
    $id = $row['teacher_id'];

    //generate random code
    $code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).$id;

    $date_added = time();
    //insert code
    $insert = mysqli_query($con, "INSERT INTO password_resets(user_id, email, code, date_added, a_type) VALUES ('$id','$email','$code','$date_added','teacher')");

    //send the email

    $url = "$baseUrl/reset_page.php?email=$email&code=$code";
    $link = "<a href=\"$url\" style=\"-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #052d3d; background-color: #42a8de; border-radius: 14px; -webkit-border-radius: 14px; -moz-border-radius: 14px; width: auto; width: auto; border-top: 1px solid #42a8de; border-right: 1px solid #42a8de; border-bottom: 1px solid #42a8de; border-left: 1px solid #42a8de; padding-top: 5px; padding-bottom: 5px; font-family: 'Lato', Tahoma, Verdana, Segoe, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;\" target=\"_blank\"><span style=\"padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;\">
<span style=\"font-size: 16px; line-height: 2; mso-line-height-alt: 32px;\"><span style=\"font-size: 14px; line-height: 28px; color: #fff !important\">Click Here to Reset Password</span></span>
</span></a>";

    $subject = "Di-Class Password Reset";
    $message = "<p>Hi $name, you recently requested to change your Di-Class teacher account password</p>";
    $message .= "<p>Kindly click on the link below to reset your password!</p>";
    $message .= "<p align='center'>$link</p>";
    $message .= "<p>Alternatively, you can also copy and paste the link below in your web browser!</p>";
    $message .= "<p align='center'>$url</p>";

    send_email($subject,"$email","$message");

    //exit();

    addAlert('success', 'A password reset link has been sent to your email!<br>Note that the link expires in one hour!');
    header("location:login_page.php");
    exit();
}


    }

   
} else {
    header('Location index.php');
}
