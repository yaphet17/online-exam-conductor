<?php
session_start();
require('../config.php');
?>
<!doctype html>
<html lang="en" dir="ltr">

<!-- soccer/project/login.html  07 Jan 2020 03:42:43 GMT -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">


<title>ONEC Login</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Core css -->
<link rel="stylesheet" href="../assets/css/main.css"/>
<link rel="stylesheet" href="../assets/css/theme1.css"/>

</head>
<body class="font-montserrat">

<div class="auth">
    <div class="auth_left">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Login to your account</div>
                <div class="form-group">
                    <select class="custom-select">
                        <option>Conductor</option>
                    </select>
                </div>
                <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail1" name='uname' value="<?php if(isset($_COOKIE["cond-uname"])) { echo $_COOKIE["cond-uname"]; } ?>" aria-describedby="emailHelp" placeholder="Enter Username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="exampleInputPassword1" name='pass' value="<?php if(isset($_COOKIE["cond-pass"])) { echo $_COOKIE["cond-pass"]; } ?>" placeholder="Password">
					<label class="form-label"><a href="forgot-password.php" class="float-right small">I forgot password</a></label>
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" name='remember' class="custom-control-input" <?php if(isset($_COOKIE['cond-uname'])){echo "checked";}?>/>
                    <span class="custom-control-label">Remember me</span>
                    </label>
                </div>
                <div class="form-group">
                    <?php
                    if(isset($_POST['signin'])){
                      //Remember login credentials
                      if(!empty($_POST["remember"])) {
	                       setcookie ("cond-uname",$_POST["uname"],time()+ 3600);
	                       setcookie ("cond-pass",$_POST["pass"],time()+ 3600);
                        } else {
	                         setcookie("cond-uname","");
	                         setcookie("cond-pass","");
                        }

                      $uName=$_POST['uname'];
                      $pass=$_POST['pass'];

                      //Sanitize user input
                      $uName=stripcslashes($uName);
                      $pass=stripcslashes($pass);

                      $query="SELECT username,password,email FROM conductor WHERE username='".$uName."' OR email='".$uName."'";
                      $result=mysqli_query($con,$query) or die("Error to send query");
                      $numRows=mysqli_num_rows($result);
                      if($numRows!=0){
                        $row=mysqli_fetch_assoc($result) or die("Error to fetch query");
                        if(password_verify($pass,$row['password'])){
                            $_SESSION['conductorId']=$uName;
                            $_SESSION['pass']=$pass;
                            $_SESSION['level']='26c2b89';
                            header('Location: dashboard.php');

                        }else{
                        echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:20px;color:red;margin-right:5px;'></i>Invalid Password</p>";
                        }
                      }else{
                        echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:20px;color:red;margin-right:5px;'></i>Invalid Username</p>";
                      }

                    } ?>
                </div>
                <div class="form-footer">
                    <input type='submit' class="btn btn-primary btn-block" name='signin' value="Sign in" title="Sign in">
                </div>
              </form>
            </div>

        </div>
    </div>
    <div class="auth_right full_img"></div>
</div>

<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/js/core.js"></script>
</body>

<!-- soccer/project/login.html  07 Jan 2020 03:42:43 GMT -->
</html>
