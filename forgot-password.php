<?php
include('config.php');
?>

<!doctype html>
<html lang="en" dir="ltr">

<!-- soccer/project/forgot-password.html  07 Jan 2020 03:42:43 GMT -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">


<title>ONEC Forgot Password</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Core css -->
<link rel="stylesheet" href="assets/css/main.css"/>
<link rel="stylesheet" href="assets/css/theme1.css"/>


</head>
<body class="font-montserrat">

<div class="auth">
    <div class="auth_left">
        <div class="card">
            <div class="text-center mb-5">
                <a class="header-brand" href="index-2.html"><img src='passport.png'></a>
            </div>
            <div class="card-body">
              <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
                <div class="card-title">Forgot password</div>
                <p class="text-muted">Enter your email address and your password will be reset and emailed to you.</p>
                <div class="form-group">
                    <label class="form-label" for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" name='email' id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                </div>
                <div class="form-footer">
                    <button type="submit" id='send' name='send' class="btn btn-primary btn-block">Send me new password</button>
                </div>
              </form>
            </div>
            <div class="text-center text-muted">
              <?php
              if(isset($_POST['send'])){
                $email=$_POST['email'];

                //Sanitize user input
                $email=stripcslashes($email);
                $query="SELECT * FROM administrator WHERE email='".$email."';";
                $result=mysqli_query($con,$query);
                $numRows=mysqli_num_rows($result);
                if($numRows===1){
                  $newPass=bin2hex(openssl_random_pseudo_bytes(7));
                  $hashed=password_hash($newPass,PASSWORD_DEFAULT);
                  $query2="UPDATE administrator SET password='".$hashed."'  WHERE email='".$email."'";
                  if(mysqli_query($con,$query2)){
                    $to=$email;
                    $subject="ONEC account password reset";
                    $msg="Your account password has been resetted\nHere is your new Password ".$newPass."\nPlease make sure to change your password immediatley!";
                    if(mail($to,$subject,$msg)){
                      echo "<p style='color:green;'><i class='fa fa-check' style='font-size:24px;color:green;margin-right:5px;'></i>Email successfully sent!</p>";
                    }else{
                        echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:24px;color:red;margin-right:5px;'></i>Email sending failed</p>";
                    }
                  }
                }else{
                  echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:24px;color:red;margin-right:5px;'></i>There is no account by this email</p>";
                }
              }
               ?>

            </div>
            <div class="text-center text-muted">
                Forget it, <a href="admin-login.php">Send me Back</a> to the Sign in screen.
            </div>
        </div>
    </div>
    <div class="auth_right full_img"></div>
</div>

<script src="assets/bundles/lib.vendor.bundle.js"></script>
<script src="assets/js/core.js"></script>
</body>

<!-- soccer/project/forgot-password.html  07 Jan 2020 03:42:43 GMT -->
</html>
