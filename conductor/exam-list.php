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


//Get conductor name
$cnQuery="SELECT firstName AS fn,lastName AS lsn  FROM conductor WHERE username='".$conductorId."'";
$cnResult=mysqli_query($con,$cnQuery) or die('Error to send query');
$cnRow=mysqli_fetch_assoc($cnResult) or die('Error to fetch query');
$conductorName=$cnRow['fn']." ".$cnRow['lsn'];


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
if(isset($_POST['cancel'])){
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

//Submitting user input
if(isset($_POST['create'])){
  $examTitle=$_POST['examTitle'];
  $examInstruct=$_POST['examInstruct'];
  $examDT=$_POST['examDateTime'];
  $examDuration=$_POST['examDuration'];
  $totalQuestion=$_POST['totalQuestion'];
  $markPRA=$_POST['markPRA'];
  $markPWA=$_POST['markPWA'];

  //Sanitize user input
  $examTitle=stripcslashes($examTitle);
  $examInstruct=stripcslashes($examInstruct);
  $examDuration=stripcslashes($examDuration);
  $totalQuestion=stripcslashes($totalQuestion);
  $markPRA=stripcslashes($markPRA);
  $markPWA=stripcslashes($markPWA);

  //Current date and time
  $examCD=date('y-m-d h:i:s');

  //Generate exam code
  $examCode=bin2hex(openssl_random_pseudo_bytes(7));

  //Convert datetime format
  $examDateTime=explode('T',$_POST['examDateTime']);
  $examDateTime=$examDateTime[0]." ".$examDateTime[1];

  $columns="conductorId,examTitle,examInstruction,examCreationDate,examDateTime,examDuration,totalQuestion,marksPerRightAnswer,marksPerWrongAnswer,examCode,examStatus";

   $query="INSERT INTO examination (".$columns.") VALUES('".$conductorId."','".$examTitle."','".$examInstruct."','".$examCD."','".$examDT."','".$examDuration."',".$totalQuestion.",".$markPRA.",".$markPWA.",'".$examCode."','created')";
   mysqli_query($con,$query) or die('Error to send query');

   $eiQuery="SELECT examId FROM examination WHERE conductorId='".$conductorId."' AND examCreationDate='".$examCD."' LIMIT 1";
   $eiResult=mysqli_query($con,$eiQuery) or die('Error to send query');
   $eiRow=mysqli_fetch_assoc($eiResult) or die('Error to fetch query');
   $examId=$eiRow['examId'];
   $redirect="Location: add-question.php?examId=".$examId;
    header($redirect);

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



<!-- Core css -->
<link rel="stylesheet" href="../assets/css/main.css"/>
<link rel="stylesheet" href="../assets/css/theme1.css"/>
<style>
.tag-default:hover{
  cursor: pointer;
}
.select-cand:hover{
  cursor:pointer;
}
</style>
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
                        <h1 class="page-title">Examination</h1>
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
          <div class="section-body">
            <div class="container-fluid">
                   <div class="d-flex justify-content-between align-items-center">
                       <ul class="nav nav-tabs page-header-tab">
                           <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#exam-list">Exams List</a></li>
                           <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#create-exam">Create Exam</a></li>
                       </ul>
                   </div>
               </div>
            </div>
            <div class="section-body mt-3">
            <div class="container-fluid">

                  <div class="tab-content">
                  <div class="tab-pane fade show active" id="exam-list" role="tabpanel">
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
                                          <th>Status</th>
                                          <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $examQuery="SELECT * FROM examination WHERE conductorId='".$conductorId."' ORDER BY examCreationDate DESC,examDateTime DESC";
                                      $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
                                      $i=1;
                                      if(mysqli_num_rows($examResult)){
                                        while($examRow=mysqli_fetch_assoc($examResult)){
                                          $examId=$examRow['examId'];
                                          $str1='#';
                                          $str2='#';
                                          $str3='#';
                                          $str4='#';
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
                                        echo "<tr>
                                              <td><input type='checkbox' class='select-cand'  id='".$i."' name='".$examId."'></td>
                                              <td><a href='exam-detail.php?examId=".$examRow['examId']."'>".$examRow['examTitle']."</td>
                                              <td><span>".$examRow['examCreationDate']."</td><td>".$examRow['examDateTime']."</span></td>
                                              <td><span>".$examRow['examDuration']."</td>
                                              <td><span>".$examRow['examStatus']."</span></td>
                                              <td><a href='".$str1."'><span class='tag tag-default' style='margin-right:5px;'>Start</span></a>
                                              <a href='".$str2."'><span class='tag tag-default' style='margin-right:5px;'>Suspend</span></a>
                                              <a href='".$str3."'><span class='tag tag-default' style='margin-right:5px;'>Unsuspend</span></a>
                                              <a href='".$str4."'><span class='tag tag-default' style='margin-right:5px;'>Cancel</span></a></td></tr>";
                                        $i++;
                                      }
                                    }else{
                                      echo "<tr><td style='text-align:center;' colspan='7'>No exam found</td></tr>";
                                    }
                                      ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="card">

                          <div class="form-group row">
                              <div class="col-md-7">
                                  <button type="submit" name='start' class="btn btn-primary">Start</button>
                                  <button type="submit" name='suspend' class="btn btn-primary">Suspend</button>
                                  <button type="submit" name='unsuspendt' class="btn btn-primary">Unsuspend</button>
                                  <button type="submit" name='cancel' class="btn btn-primary">Cancel</button>
                              </div>
                          </div>

                            </div>
                        </div>
                          </form>
                    </div>

                    <div class="tab-pane fade" id="create-exam" role="tabpanel">
                          <div class="card">
                              <div class="card-header">
                                  <h3 class="card-title">Creat Exam</h3>
                              </div>
                              <form  action="<?=$_SERVER['PHP_SELF']?>" method='POST' class="card-body">
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Exam Title <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text' name='examTitle' class="form-control" >
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Exam Instruction <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text' name='examInstruct' class="form-control" >
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Exam Date and Time <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='datetime-local' name='examDateTime' step='1' class="form-control">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label"  >Duration <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text' name='examDuration' placeholder="HH : MM : SS" class="form-control">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Total Question <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text'  name='totalQuestion' class="form-control">
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Marks Per Right Answer <span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text' name='markPRA' class="form-control">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label">Penality Per Wrong Answer<span class="text-danger">*</span></label>
                                      <div class="col-md-7">
                                          <input type='text' name='markPWA' class="form-control">
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <label class="col-md-3 col-form-label"></label>
                                      <div class="col-md-7">
                                          <button type="submit" name='create' class="btn btn-primary">Create</button>
                                          <button type="submit" class="btn btn-outline-secondary">Cancel</button>
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
</div>


<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>
</html>
