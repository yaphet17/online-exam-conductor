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

if(isset($_GET['examId']) AND !empty($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{
  die('exam id not found');
}


//Get conductor name
$cnQuery="SELECT firstName AS fn,lastName AS lsn  FROM conductor WHERE username='".$conductorId."'";
$cnResult=mysqli_query($con,$cnQuery) or die('Error to send query');
$cnRow=mysqli_fetch_assoc($cnResult) or die('Error to fetch query');
$conductorName=$cnRow['fn']." ".$cnRow['lsn'];

$query="SELECT * FROM examination e,conductor c WHERE  e.examId=".$examId;
$result=mysqli_query($con,$query) or die('Error to send query');
$row=mysqli_fetch_assoc($result) or die('Error to fetch query');
$examTitle=$row['examTitle'];
$prefix=$row['prefix'];
$fname=$row['firstName'];
$lname=$row['lastName'];
$cDate=$row['examCreationDate'];
$eDate=$row['examDateTime'];
$eDura=$row['examDuration'];
$totQ=$row['totalQuestion'];
$mpr=$row['marksPerRightAnswer'];
$mpw=$row['marksPerRightAnswer'];
$code=$row['examCode'];
$status=$row['examStatus'];

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
                <li class="active"><a href="exam-list.php"><i class="fa fa-list-ul"></i><span>Examinations</span></a></li>
                <li><a href="candidate-list.php"><i class="fa fa-list-ul"></i><span>Candidates</span></a></li>
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
                        <h1 class="page-title">Exam Details</h1>
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
                    <div class="col-lg-8 col-md-12 mt-4">
                        <div class="card">
                          <div class="card-header">
                              <a href='<?php if($status=='created' or $status=='started') {echo "add-to-exam.php?examId=".$examId;}else{echo '#';}?>' class="card-title" style='margin-right:15px;'>Add candidate</a>
                              <a href='<?php if($status=='created') {echo "add-question.php?examId=".$examId;}else{echo '#';}?>' class="card-title" style='margin-right:15px;'>Add question</a>
                              <a href='#' class="card-title" style='margin-right:15px;'>Spreadsheet report</a>
                              <a href='generate-pdf.php?examId=<?php echo $examId;?>' class="card-title" style='margin-right:15px;'>Pdf report</a>
                          </div>
                            <div class="card-body">
                                <div class="timeline_item ">
                                    <span><a href="javascript:void(0);"><?php echo $examTitle;?></a>
                                      <a href="exam-preview.php?examId='<?php echo $examId;?>'" class="float-right text-right btn btn-primary">Exam Preview</a>
                                      </span>

                                    <div class="dropdown-divider"></div>
                                    <div class="msg">
                                      <?php

                                      echo "<p>Conducted By: ".$prefix." ".$fname." ".$lname."</p>
                                           <p>Created On: ".$cDate."</p>
                                           <p>Starts in: ".$eDate."</p>
                                           <p>Duration: ".$eDura."</p>
                                           <p>Total Question: ".$totQ."</p>
                                           <p>Marks Per Right Answer: ".$mpr."</p>
                                           <p>Marks Per Wrong Answer: ".$mpw."</p>
                                           <p>Code: ".$code."</p>
                                           <p>Status: ".$status."</p><br>";
                                           $examStatus=false;
                                           if($status==='completed'){
                                             $examStatus=true;
                                           }
                                      ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive">
                            <table id='examreport' class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                  <thead>
                                    <tr>
                                      <th colspan='6'>Candidates enrolled to the exam</th>
                                    </tr>
                                    <tr>
                                      <th>Name</th>
                                      <th>Section</th>
                                      <th>Department</th>
                                      <th>Academic Year</th>
                                      <th>Attendance Status</th>
                                      <th>Result</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $candidateQuery="SELECT * FROM candidate c,examenrollment e WHERE c.candidateId=e.candidateId AND e.examId=".$examId." ORDER BY c.firstName,c.lastName,c.registrationDate DESC";
                                    $candidateResult=mysqli_query($con,$candidateQuery);
                                    if(mysqli_num_rows($candidateResult)!=0){
                                    while($candidateRow=mysqli_fetch_assoc($candidateResult)){
                                      //Fetch section data
                                      $sectionQuery="SELECT * FROM section WHERE sectionId='".$candidateRow['sectionId']."'";
                                      $sectionResult=mysqli_query($con,$sectionQuery) or die('Error to send query');
                                      $sectionRow=mysqli_fetch_assoc($sectionResult) or die('Error to fetch query');
                                      //Fetch candidates exam result
                                      echo "<tr><td><a href='candidate-detail.php?candidateId=".$candidateRow['candidateId']."'>".$candidateRow['firstName']." ".$candidateRow['lastName']."</a></td>
                                                <td><span>".$sectionRow['sectionName']."</span></td>
                                                <td><span>".$sectionRow['department']."</span></td>
                                                <td><span>".$sectionRow['academicYear']."</span></td>
                                                <td><span class='tag tag-default'>".$candidateRow['attendanceStatus']."</span></td>";
                                      if($examStatus){
                                        $markQuery="SELECT maximumMark AS mm,obtainedMark AS om FROM mark WHERE candidateId='".$candidateRow['candidateId']."' AND examId=".$examId;
                                        $markResult=mysqli_query($con,$markQuery) or die('Error to send querry');
                                        if(mysqli_num_rows($markResult)!=0){
                                          $markRow=mysqli_fetch_assoc($markResult) or die('Error to fetch query');
                                          echo "<td><span>".$markRow['om']."/".$markRow['mm']."</span></td></tr>";
                                        }else{
                                          echo "<td><span>none</span></td></tr>";
                                        }
                                      }else{
                                        echo "<td><span>none</span></td></tr>";
                                      }

                                    }
                                  }else{
                                    echo "<tr><td colspan='6' style='text-align:center;'>No candidate enrolled.</td></tr>";
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

<script src="../assets/bundles/apexcharts.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>

<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>

</html>
