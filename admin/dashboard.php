<?php
session_start();
require('../config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='b893e95'){
    session_destroy();
    header('Location: index.php');
  }
}


//Current day and time
$currDateTime=date('y-m-d h:i:s');

//Fetching some status data
//Ongoing exams
$ongoQuery="SELECT COUNT(*) AS ongo FROM examination WHERE examStatus='started'";
$ongoResult=mysqli_query($con,$ongoQuery) or die('Error to send querry');
$ongoRow=mysqli_fetch_assoc($ongoResult) or die('Error to fetch query');
$ongo=$ongoRow['ongo'];
//Canceled exams
$canQuery="SELECT COUNT(*) AS cancel FROM examination WHERE examStatus='canceled' AND examDateTime='".$currDateTime."'";
$canResult=mysqli_query($con,$canQuery) or die('Error to send query');
$canRow=mysqli_fetch_assoc($canResult) or die('Error to fetch query');
$cancel=$canRow['cancel'];
//Suspended exams
$susQuery="SELECT COUNT(*) AS suspend FROM examination WHERE examStatus='suspended' AND examDateTime='".$currDateTime."'";
$susResult=mysqli_query($con,$susQuery) or die('Error to send query');
$susRow=mysqli_fetch_assoc($susResult) or die('Error to fetch query');
$suspend=$susRow['suspend'];
//Exams to be held today
$todayEQuery="SELECT COUNT(*) AS today FROM examination WHERE examDateTime='".$currDateTime."'";
$todayEResult=mysqli_query($con,$todayEQuery) or die('Error to send query');
$todayERow=mysqli_fetch_assoc($todayEResult) or die('Error to fetch query');
$todayE=$todayERow['today'];
//Candidates taking an exam now
$onexQuery="SELECT COUNT(*) AS onex FROM examenrollment WHERE attendanceStatus='attending'";
$onexResult=mysqli_query($con,$onexQuery) or die('Error to send querry');
$onexRow=mysqli_fetch_assoc($onexResult) or die('Error to fetch query');
$onex=$onexRow['onex'];
//Dispelled candidates
$dispellQuery="SELECT COUNT(*) AS dispell FROM examenrollment WHERE attendanceStatus='dispelled'";
$dispellResult=mysqli_query($con,$dispellQuery) or die('Error to send query');
$dispellRow=mysqli_fetch_assoc($dispellResult) or die('Error to fetch query');
$dispell=$dispellRow['dispell'];
//Upcoming exams
$upexQuery="SELECT COUNT(*) AS upex FROM examination WHERE examDateTime='".$currDateTime."'";
$upexResult=mysqli_query($con,$upexQuery) or die('Error to send query');
$upexRow=mysqli_fetch_assoc($upexResult) or die('Error to fetch query');
$upex=$upexRow['upex'];

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
    <div id="left-sidebar" class="sidebar">
        <h5 class="brand-name">ONEC </h5>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul class="metismenu">
                <li class="g_heading">Navigation</li>
                <li class="active"><a href="dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                <li><a href="exam-list.php"><i class="fa fa-list-ul"></i><span>Examinations</span></a></li>
                <li><a href="candidate-list.php"><i class="fa fa-list-ul"></i><span>Candidates</span></a></li>
                <li><a href="conductor-list.php"><i class="fa fa-list-ul"></i><span>Conductors</span></a></li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Authentication</span></a>
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
                        <h1 class="page-title">Dashboard</h1>
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
                    <div class="col-lg-12">
                        <div class="mb-4">
                            <h4>Daily Status</h4>
                        </div>
                    </div>
                </div>
                <div class="row clearfix row-deck">
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ongoing Exams</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $ongo;?></h5>
                                <span class="font-12">exams which are happening right now.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Canceled Exams</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $cancel;?></h5>
                                <span class="font-12">exams which are canceled today.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Suspended Exams</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $suspend;?></h5>
                                <span class="font-12">exams which are suspended today.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Exams To Be Held Today</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $todayE;?></h5>
                                <span class="font-12">exams which will be conducted today.</span>
                            </div>
                        </div>
                    </div>
					          <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Candidates On Exams</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $onex;?></h5>
                                <span class="font-12">Candidates taking an exam right now.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Dispelled Candidates</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $dispell;?></h5>
                                <span class="font-12">Candidates dispelled from the exams today.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Upcoming Exams</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="number mb-0 font-32 counter"><?php echo $upex;?></h5>
                                <span class="font-12">Exams scheduled to be held.</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="section-body">
            <div class="container-fluid">
                <div class="row clearfix row-deck">
                    <div class="col-xl-4 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ongoing exams</h3>

                            </div>
                            <table class="table card-table mt-2">
                                <tbody>
                                      <?php
                                      $ongoingQuery="SELECT examTitle,examDateTime,examDuration FROM examination WHERE examStatus='started' ORDER BY examDateTime DESC LIMIT 10";
                                      $ongoingResult=mysqli_query($con,$ongoingQuery) or  die("Error to send query");
                                      while($ongoingRow=mysqli_fetch_assoc($ongoingResult)){
                                        echo
                                        "<tr>
                                          <td>
                                              <p class='mb-0 d-flex justify-content-between'><span>".$ongoingRow['examTitle']."</span><strong>".$ongoingRow['examDuration']."</strong></p>
                                              <span class='text-muted font-13'>".$ongoingRow['examDateTime']."</span>
                                          </td>
                                          </tr>";
                                      }

                                      ?>


                                </tbody>
                            </table>
                        </div>
                    </div>


                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/apexcharts.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>

</body>

<!-- soccer/project/  07 Jan 2020 03:37:22 GMT -->
</html>
