<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login -DiClass</title>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
</head>
<body>
    <div class="signup-container signup_login">
        <header>
            <a href="index.php"><img src="assets/logo.png" alt="Team Dinlas"></a>
            <h1>Welcome back!</h1>
            <p>New to DiClass? <a href="signup_page.php">Sign Up</a></p>
            <br>
            <!-- we display proper error or success messages -->
            <?php 
            include 'includes/functions.php';
            
            echo showAlert(); ?>
        </header>
        <form action="login.php" method="POST" class="login-form">
           
            <label for="email">
                <input type="email" name="email" id="email" placeholder="example@gmail.com">
            </label>

            <label for="password">
                <input type="password" name="password" id="password" placeholder="Password">
            </label>

            <label for="type">
                <select name="type" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </label>
    
            <button type="submit" name="login" id="login">Login</button>
            <div class="row">
                <p class="col-sm-6 col-xs-12">Forget Password? <a data-toggle="modal" href='#modal-id'>Reset</a></p> <!--Forgot password modal added-->
                <p class="col-sm-6 col-xs-12"><a href="index.php" class="text-center"> << Go Back Home</a></p>
            </div>
        </form>

    </div>


<!--sign up modal-->

    <div class="modal" id="modal-id">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Reset Password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="signup-container signup_login" style="margin: 0;">
                        <form action="reset.php" method="POST" class="login-form">

                            <label for="email-2">
                                <input type="email" name="email" id="email-2" placeholder="example@gmail.com">
                            </label>

                            <label for="type">
                                <select name="type" required>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </label>

                            <button type="submit" name="reset" >Reset Password</button>

                        </form>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script> <!--Jquery included-->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script> <!--bootstrap js included-->
</body>
</html>