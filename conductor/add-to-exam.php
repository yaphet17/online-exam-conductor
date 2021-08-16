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


//Copy url value
if(isset($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{
  //Alternate option if a page is refreshed by submit button
  if(isset($_POST['examId'])){
    $examId=$_POST['examId'];
  }else{
    die('exam id not found');
  }
}

//Add all student with in a section to exam
if(isset($_POST['addSection'])){
  $getSQuery="SELECT sectionId AS si FROM section";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($sRow=mysqli_fetch_assoc($getSResult)) {
    if(isset($_POST[$sRow['si']])){
      $candidateQuery="SELECT candidateId AS ci FROM candidate WHERE sectionId=".$sRow['si'];
      $candidateResult=mysqli_query($con,$candidateQuery) or die('Error to send query');
      while($candidateRow=mysqli_fetch_assoc($candidateResult)){
        $checkQuery="SELECT COUNT(*) AS n FROM examenrollment WHERE candidateId='".$candidateRow['ci']."' AND examId='".$examId."'";
        $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
        $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch query');
        if($checkRow['n']==0){
          $query="INSERT INTO examenrollment (candidateId,examId,attendanceStatus) VALUES('".$candidateRow['ci']."',".$examId.",'notattended')";
          mysqli_query($con,$query) or die('Error to send query');
        }
      }
    }
  }
}

//Add all selected candidates to exam
if(isset($_POST['addCandidate'])){
  $getQuery="SELECT candidateId AS ci,firstName,lastName,email FROM candidate";
  $getResult=mysqli_query($con,$getQuery);
  while($getRow=mysqli_fetch_assoc($getResult)){
    if(isset($_POST[$getRow['ci']])){
      $checkQuery="SELECT COUNT(*) AS n FROM examenrollment WHERE candidateId='".$getRow['ci']."' AND examId='".$examId."'";
      $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
      $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch query');
      if($checkRow['n']==0){
        $insertQuery="INSERT INTO examenrollment (candidateId,examId,attendanceStatus) VALUES('".$getRow['ci']."',".$examId.",'notattended')";
        mysqli_query($con,$insertQuery) or die('Error to send query');
        $emailQuery="SELECT examTitle,examDateTime,examCode FROM examination WHERE examId='".$examId."' LIMIT 1";
        $emailResult=mysqli_query($con,$emailQuery) or die('Error to send query');
        $emailRow=mysqli_fetch_assoc($emailResult) or die('Error to fetch query');
        $eName=$emailRow['examTitle'];
        $eDateTime=$emailRow['examDateTime'];
        $eCode=$emailRow['examCode'];
        $cName=$getRow['firstName']." ".$getRow['lastName'];
        $to=$getRow['email'];
        $subject="Exam Notification";
        $msg="Dear ".$cName." you are added to exam ".$eName." that will be held on ".$eDateTime." below is your exam code\nExam Code=".$eCode;
        if(mail($to,$subject,$msg)){
            //Email sucessful
        }else{
          die('email sending failed');
        }
      }else{
        die('Already enrolled');
      }

    }
  }
}
//Redirect to exam list page
if(isset($_POST['done'])){
  header('Location: exam-list.php');
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

    <div class="page" style='margin:0;left:0;right:0;width:100%;'>
        <div id="page_top" class="section-body top_dark">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <h1 class="page-title">Add candidates to exam</h1>
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
                    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlentities($_GET['examId']);}?>">
                    <div class="row">
                      <div class="col-lg-2 col-md-4 col-sm-6">

                          <div class="input-group">
                                    <select name="sectionFilter" class="form-control show-tick">
                                        <option value='all' selected hidden>Section</option>
                                        <?php
                                          //Get maximum section number from db
                                          $checkSecQuery="SELECT MAX(sectionName) AS maxS FROM section LIMIT 1";
                                          $checkSecResult=mysqli_query($con,$checkSecQuery) or die('Error to send query');
                                          $checkSecRow=mysqli_fetch_assoc($checkSecResult);
                                          $maxSL=explode(' ',$checkSecRow['maxS']);
                                          $maxS=end($maxSL);
                                          for($i=1;$i<=$maxS;$i++){
                                            echo "<option value='section ".$i."'>".$i."</option>";
                                          }
                                          ?>
                                    </select>
                          </div>
                      </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="input-group">
                                      <select name='academicYear' class="form-control show-tick">
                                          <option value='all' selected hidden>AcademicYear</option>
                                          <option value='1'>1st Year</option>
                                          <option value='2'>2nd Year</option>
                                          <option value='3'>3rd Year</option>
                                          <option value='4'>4th Year</option>
                                          <option value='5'>5th Year</option>
                                      </select>

                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">

                            <div class="input-group">
                                      <select name='department' class="form-control show-tick">
                                          <option selected hidden value='all'>Department</option>
                                        <?php
                                        //Get all department from db
                                        $dQuery="SELECT department AS d FROM section GROUP BY department";
                                        $dResult=mysqli_query($con,$dQuery) or die('Error to send query');
                                        while($dRow=mysqli_fetch_assoc($dResult)){
                                          echo "<option value='".$dRow['d']."'>".$dRow['d']."</option>";
                                        }
                                        ?>
                                      </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <input type='submit' class="btn btn-primary btn-block"  name='filter' value='Filter'>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Sex</th>
                                    <th>Section</th>
                                    <th>Academic Year</th>
                                    <th>Department</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $rows="c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,s.sectionName AS section,s.academicYear AS year,s.department AS depart ";
                              $query="SELECT ".$rows." FROM section s,candidate c  WHERE c.sectionId=s.sectionId";
                              //Filter candidates
                              if(isset($_POST['filter'])){
                                if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                                    $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']==="all"){
                                    $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                                    $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                                    $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                                  $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                                  $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                                    $query="SELECT ".$rows." FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                }
                              }

                              $result=mysqli_query($con,$query) or die('Error to send query');
                              if(mysqli_num_rows($result)!=0){
                              while($row=mysqli_fetch_assoc($result)){
                                $candidateId=$row['ci'];
                                if($row['sx']=='m'){
                                  $sex='Male';
                                }else{
                                  $sex='Female';
                                }

                                echo "<tr><td><label class='custom-control custom-checkbox select-cand'><input type='checkbox' class='custom-control-input' name='".$candidateId."'><span class='custom-control-label'>&nbsp;</span></label></td>
                                          <td><a href='candidate-detail.php?candidateId=".$candidateId."'>".$row['fname']." ".$row['lname']."</a></td>
                                          <td><span>".$sex."</span></td>
                                          <td><span>".$row['section']."</span></td>
                                          <td><span>".$row['year']."</span></td>
                                          <td><span>".$row['depart']."</span></td>
                                          <td><span>".$row['email']."</span></td></tr>";
                              }
                            }else{
                              echo "<tr><td colspan='9' style='text-align:center;'>No candidate found</td></tr>";
                            }
                              ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="card" style='margin-top:20px;'>
                      <div class="form-group row">
                          <div class="col-md-7">
                              <button type="submit" name='addCandidate' class="btn btn-primary">Add</button>
                              <button type="submit" name='done' class="btn btn-primary">Done</button>
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
