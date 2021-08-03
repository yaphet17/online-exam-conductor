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


if(isset($_GET['candidateId']) and !empty($_GET['candidateId'])){
  $candidateId=htmlentities($_GET['candidateId']);
}else{
  die('candidate id not found!');
}

//Get conductor name
$cnQuery="SELECT firstName AS fn,lastName AS lsn  FROM conductor WHERE username='".$conductorId."'";
$cnResult=mysqli_query($con,$cnQuery) or die('Error to send query');
$cnRow=mysqli_fetch_assoc($cnResult) or die('Error to fetch query');
$conductorName=$cnRow['fn']." ".$cnRow['lsn'];

//Fetching candidate's information from db
$query="SELECT * FROM candidate c,section s WHERE c.sectionId=s.sectionId AND c.candidateId='".$candidateId."'";
$result=mysqli_query($con,$query) or die('Error to send query');
$row=mysqli_fetch_assoc($result) or die('Error to fetch query');
$imagePath=$row['candidateImage'];
$fname=$row['firstName'];
$lname=$row['lastName'];
$sex=$row['sex'];
$regDate=$row['registrationDate'];
$section=$row['sectionName'];
$email=$row['email'];
$vCode=$row['verificationCode'];
$vStatus=$row['verificationStatus'];

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
                <li class="active"><a href="candidate-list.php"><i class="fa fa-list-ul"></i><span>Candidates</span></a></li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Profile</span></a>
                    <ul>
                        <li><a href="profile.php">Profile information</a></li>
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
                        <h1 class="page-title">Candidate Details</h1>
                    </div>
                    <div class="right">
                      <div class="notification d-flex">
                          <div class="dropdown d-flex">
                              <a class="nav-link  d-none d-md-flex btn btn-default btn-icon ml-2"><?php echo $conductorName;?></a>

                          </div>
                      </div>
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
                    <div class="col-lg-4 col-md-12 mt-4 text-right">
                        <div class="card c_grid c_yellow">
                            <div class="card-body text-center">
                                <div class="circle">
                                    <img class="rounded-circle" src="<?php echo $imagePath;?>" alt="profile photo">
                                </div>
                                <h6 class="mt-3 mb-0"><?php echo $fname." ".$lname;?></h6>
                                <span><?php echo $email;?></span>
                            </div>
                        </div>
                        </div>


                    <div class="col-lg-8 col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Personal Details</h3>
                                <div class="card-options">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timeline_item ">
                                    <span><a href="javascript:void(0);"><?php echo $fname." ".$lname;?></a><small class="float-right text-right"><?php echo $regDate;?></small></span>
                                    <div class="dropdown-divider"></div>
                                    <div class="msg">
                                        <?php
                                          echo"<p>First Name: ".$fname."</p>
                                               <p>First Name: ".$lname."</p>
                                               <p>Sex: ".$sex."</p>
                                               <p>Registration Date: ".$regDate."</p>
                                               <p>Section: ".$section."</p>
                                               <p>Email: ".$email."</p>
                                               <p>Verification Code: ".$vCode."</p>
                                               <p>Verification Status: ".$vStatus."</p>";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                  <thead>
                                    <tr>
                                      <th colspan='6'>Exams Taken</th>
                                    </tr>
                                    <tr>
                                      <th>Exam Title</th>
                                      <th>Exam Status</th>
                                      <th>Attendance Status</th>
                                      <th>Maximum Mark</th>
                                      <th>Obtained Mark</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                       <?php
                                       $examQuery="SELECT e.examId,e.examTitle,e.examStatus,e.marksPerRightAnswer,e.totalQuestion,ee.attendanceStatus FROM examination e,examenrollment ee WHERE e.examId=ee.examId AND ee.candidateId='".$candidateId."' ORDER BY examDateTime DESC,examCreationDate DESC";
                                       $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
                                       while($examRow=mysqli_fetch_assoc($examResult)){
                                         $examId=$examRow['examId'];
                                         $markQuery="SELECT maximumMark,obtainedMark FROM mark WHERE examId=".$examId." AND candidateId='".$candidateId."'";
                                         $markResult=mysqli_query($con,$markQuery) or die('Error to send query');
                                         if(mysqli_num_rows($markResult)!=0){
                                             $markRow=mysqli_fetch_assoc($markResult) or die('Error to fetch query');
                                             $mark=$markRow['obtainedMark'];
                                         }
                                         $status="none";
                                         $mark="none";
                                         if($examRow['examStatus']==='completed'){
                                           $status=$examRow['attendanceStatus'];
                                         }
                                         echo "<tr><td><a href='exam-detail.php?examId=".$examId."'>".$examRow['examTitle']."</a></td>
                                             <td><span>".$examRow['examStatus']."</span></td>
                                             <td><span class='tag tag-default'>".$status."</span></td>
                                             <td><span>".$examRow['marksPerRightAnswer']*$examRow['totalQuestion']."</span></td>
                                             <td><span>".$mark."</span></td>
                                             </tr>";

                                       }

                                       ?>
                               </tbody>
                               </table>
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
