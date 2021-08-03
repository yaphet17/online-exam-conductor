<?php
session_start();
require('../config.php');

$conductorId=$_SESSION['conductorId'];
$pass=$_SESSION['pass'];
if(!isset($conductorId) or !isset($pass) or !isset($_SESSION['level']) or empty($conductorId) or empty($pass) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}


//Fetching conductor's information from db
$query="SELECT* FROM conductor WHERE username='".$conductorId."'";
$result=mysqli_query($con,$query) or die('Error to send query');
$row=mysqli_fetch_assoc($result) or die('Error to fetch query');
$prefix=$row['prefix'];
$fname=$row['firstName'];
$lname=$row['lastName'];
$role=$row['role'];
$email=$row['email'];
$code=$row['verificationCode'];
$status=$row['verificationStatus'];

?>



<!doctype html>
<html lang="en" dir="ltr">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">


<title>ONEC</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Plugins css -->
<link rel="stylesheet" href="../assets/plugins/charts-c3/c3.min.css"/>

<!-- Core css -->
<link rel="stylesheet" href="../assets/css/main.css"/>
<link rel="stylesheet" href="../assets/css/theme1.css"/>
</head>

<body class="font-montserrat">
<!-- Page Loader -->
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
                <li class='active'>
                    <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Profile</span></a>
                    <ul>
                        <li class='active'><a href="profile.php">Profile information</a></li>
                    </ul>
                    <ul>
                        <li><a href="change-password.php">Change password</a></li>
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
                        <h1 class="page-title">Conductor Details</h1>
                    </div>
                    <div class="right">

                        <div class="notification d-flex">
                            <div class="dropdown d-flex">
                                <a class="nav-link icon d-none d-md-flex btn btn-default btn-icon ml-2" data-toggle="dropdown"><i class="fa fa-user"></i></a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="logout.php"><i class="dropdown-icon fa fa-sign-out"></i> Sign out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		 <div class="section-body mt-3">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Personal Details</h3>
                                <div class="card-options">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timeline_item ">
                                    <span><a href="javascript:void(0);"><?php echo $prefix." ".$fname." ".$lname;?></a></span>
                                    <div class="dropdown-divider"></div>
                                    <div class="msg">
                                        <?php
                                        echo "<p>Name: ".$prefix." ".$fname." ".$lname."</p>
                                                <p>Role: ".$role."</p>
                                                <p>Email: ".$row['email']."</p>
                                                <p>Verification Code: ".$code."</p>
                                                <p>Verification Status: ".$status."</p>";
                                        ?>
                                    </div>
                                </div>
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

</html>
