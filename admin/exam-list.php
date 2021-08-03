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

//Start selected exams
if(isset($_POST['start'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='started' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}
//Suspend selected exams
if(isset($_POST['suspend'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='suspended' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}

//Unsuspend selected exams
if(isset($_POST['unsuspend'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='suspend'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='created' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}
//Cancel selected exams
if(isset($_POST['delete'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created' or  $eRow['es']==='suspended'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='created' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}

//Delete selected exams
if(isset($_POST['cancel'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created' or  $eRow['es']==='suspended'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="DELETE FROM examination WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}


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
                <li><a href="conductor-list.php"><i class="fa fa-list-ul"></i><span>Conductors</span></a></li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Authentication</span></a>
                    <ul>
                        <li><a href="change-password.php">Change Password</a></li>
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
                        <h1 class="page-title">Examination</h1>
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

                  <div class="tab-content">
                  <div class="tab-pane fade show active" id="todo-list" role="tabpanel">
                  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                  <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <input type='submit' class="btn btn-primary btn-block"  name='search' value='Search'>
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
                                          <th></th>
                                          <th>Exam Name</th>
                                          <th>Creation Date</th>
                                          <th>Starting DateTime</th>
                                          <th>Duration</th>
                                          <th>Exam Code</th>
                                          <th>Status</th>
                                          <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $examQuery="SELECT * FROM examination ORDER BY examCreationDate DESC,examDateTime DESC";
                                      $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
                                      $i=1;
                                      while($examRow=mysqli_fetch_assoc($examResult)){

                                        $examId=$examRow['examId'];
                                        $str1='#';
                                        $str2='#';
                                        $str3='#';
                                        $str4='#';
                                        $str5='#';
                                        $examId=$examRow['examId'];
                                        if($examRow['examStatus']==='created'){
                                          $str1="start-exam.php?examId=".$examId;
                                          $str2="suspend-exam.php?examId=".$examId;
                                        }
                                        if($examRow['examStatus']==='created' or $examRow['examStatus']==='suspended'){
                                          $str3="cancel-exam.php?examId=".$examId;
                                        }
                                        if($examRow['examStatus']==='suspended'){
                                          $str4="unsuspend-exam.php?examId=".$examId;
                                        }
                                        if($examRow['examStatus']==='suspend' or $examRow['examStatus']==='canceled'){
                                          $str5="delete-exam.php?examId=".$examId;
                                        }

                                        echo "<tr>
                                              <td><input type='checkbox' id='".$i."' name='".$examId."'></td>
                                              <td><a href='exam-detail.php?examId=".$examRow['examId']."'>".$examRow['examTitle']."</td>
                                              <td><span>".$examRow['examCreationDate']."</td><td>".$examRow['examDateTime']."</span></td>
                                              <td><span>".$examRow['examDuration']."</td><td>".$examRow['examCode']."</span></td>
                                              <td><span>".$examRow['examStatus']."</span></td>
                                              <td><a href='".$str1."'><span class='tag tag-default' style='margin-right:5px;'>Start</span></a>
                                              <a href='".$str2."'><span class='tag tag-default' style='margin-right:5px;'>Suspend</span></a>
                                              <a href='".$str3."'><span class='tag tag-default' style='margin-right:5px;'>Unsuspend</span></a>
                                              <a href='".$str4."'><span class='tag tag-default' style='margin-right:5px;'>Cancel</span></a>
                                              <a href='".$str5."'><span class='tag tag-default' style='margin-right:5px;'>Delete</span></a></td></tr>";
                                        $i++;
                                      }
                                      ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="card">

                          <div class="form-group row">
                              <label class="col-md-3 col-form-label"></label>
                              <div class="col-md-7">
                                  <button type="submit" name='start' class="btn btn-primary">Start</button>
                                  <button type="submit" name='suspend' class="btn btn-primary">Suspend</button>
                                  <button type="submit" name='unsuspendt' class="btn btn-primary">Unsuspend</button>
                                  <button type="submit" name='cancel' class="btn btn-primary">Cancel</button>
                                  <button type="submit" name='delete' class="btn btn-primary">Delete</button>
                              </div>
                          </div>

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


<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>
</html>
