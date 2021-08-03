<?php
//Including database configuration file
session_start();
require_once('../config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='b893e95'){
    session_destroy();
    header('Location: index.php');
  }
}

$uname=$_SESSION['uname'];

$query="SELECT password,email FROM administrator WHERE username='".$uname."' LIMIT 1";
$result=mysqli_query($con,$query) or die('Error to send query');
if($row=mysqli_fetch_assoc($result)){
    $email=$row['email'];
}


?>

<!doctype html>
<html lang="en" dir="ltr">

<!-- soccer/project/  07 Jan 2020 03:36:49 GMT -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>ONEC</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Core css -->
<link rel="stylesheet" href="../assets/css/main.css"/>
<link rel="stylesheet" href="../assets/css/theme1.css"/>
</head>

<body class="font-montserrat">
<div class="page-loader-wrapper">
    <div class="loader">
    </div>
</div>

<div id="main_content">

    <div id="left-sidebar" class="sidebar ">
        <h5 class="brand-name">ONEC</h5>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul class="metismenu">
              <li class="g_heading">Navigation</li>
              <li><a href="dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
              <li><a href="exam-list.php"><i class="fa fa-list-ul"></i><span>Examinations</span></a></li>
              <li><a href="candidate-list.php"><i class="fa fa-list-ul"></i><span>Candidates</span></a></li>
              <li><a href="conductor-list.php"><i class="fa fa-list-ul"></i><span>Conductors</span></a></li>
              <li class="active">
                  <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Authentication</span></a>
                  <ul>
                      <li class="active"><a href="change-password.php">Change Password</a></li>
                  </ul>
              </li>

            </ul>
        </nav>
    </div>

    <div class="page">
        <div id="page_top" class="section-body top_dark">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <a href="javascript:void(0)" class="icon menu_toggle mr-3"><i class="fa  fa-align-left"></i></a>
                        <h1 class="page-title">Change Password</h1>
                    </div>
                    <div class="right">

                    </div>
                </div>
            </div>
        </div>


        <div class="section-body">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="tab-content">


                            <div >
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Change Password</h3>
                                    </div>
                                    <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
                                    <div class="card-body">
                                        <div class="row clearfix">
                                          <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
                                              <?php
                                              echo "<div class='col-lg-4 col-md-12'>
                                                  <div class='form-group'>
                                                  <input type='text' class='form-control' value='".$uname."'placeholder='Username' disabled>
                                                  </div>
                                                  </div>"; ?>
                                            <div class="col-lg-4 col-md-12">
                                              <?php
                                              echo "<div class='form-group'>
                                                  <input type='email' name='email' class='form-control' value='".$email."' placeholder='Email'>
                                                  </div>";?>

                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <hr>
                                                <h6>Change Password</h6>
                                                <div class="form-group">
                                                    <input type="password" name='oldPass' class="form-control" placeholder="Current Password">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" name='newPass' class="form-control" placeholder="New Password">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" name='newPass' class="form-control" placeholder="Confirm Password">
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    if(isset($_POST['save'])){
                                                      if(password_verify($_POST['oldPass'],$row['password'])){
                                                        $newPass=$_POST['newPass'];
                                                        $newEmail=$_POST['email'];
                                                        $hashed=password_hash($newPass,PASSWORD_DEFAULT);
                                                        $query3="UPDATE administrator SET password='".$hashed."',email='".$newEmail."' WHERE username='".$uname."'";
                                                        if(mysqli_query($con,$query3)){
                                                            unset($_SESSION['pass']);
                                                            $_SESSION['pass']=$hashed;
                                                            echo "<p style='color:green;'><i class='fa fa-check' style='font-size:24px;color:green;margin-right:5px;'></i>Successfully Changed</p>";
                                                        }else{
                                                          die('Some error occured!');
                                                        }
                                                      }else{
                                                        echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:24px;color:red;margin-right:5px;'></i>Invalid Password</p>";
                                                      }
                                                    }
                                                     ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 m-t-20 text-right">
                                                <button type="submit" id='save' name='save'  class="btn btn-primary">SAVE</button> &nbsp;
                                                <button type="button" class="btn btn-default">CANCEL</button>
                                            </div>

                                        </div>
                                    </div>
                                  </form>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>

            </div>

		</div>



	   </div>



<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>

<!-- soccer/project/  07 Jan 2020 03:37:22 GMT -->
</html>
