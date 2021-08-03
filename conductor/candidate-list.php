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

//Submitting registration form
if(isset($_POST['add'])){
  $id=$_POST['id'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];
  $sex=$_POST['sex'];
  $section=$_POST['section'];
  $email=$_POST['email'];
  $pass=$_POST['pass'];

  //Santizing user input
  $id=stripcslashes($id);
  $fname=stripcslashes($fname);
  $lname=stripcslashes($lname);
  $email=stripcslashes($email);
  $pass=stripcslashes($pass);
  $cImage=getPath();

  //Encrypt password
  $hashed=password_hash($pass,PASSWORD_DEFAULT);

  //Current date and posix_times
  $currDateTime=date('y-m-d h:i:s');

  //Generat verification bind_textdomain_codeset
  $verificationCode=bin2hex(openssl_random_pseudo_bytes(7));

  //Check if there a candidate with the same id or Username
  $checkQuery="SELECT count(candidateId) AS i FROM candidate WHERE candidateId='".$id."'";
  $result=mysqli_query($con,$checkQuery) or die('Error to send query');
  $checkRow=mysqli_fetch_assoc($result) or die('Error to fetch query');
  if($checkRow['i']==='0'){
    //Inserting user input to db
    $query="INSERT INTO candidate VALUES('".$id."','".$hashed."','".$fname."','".$lname."','".$sex."','".$cImage."','".$currDateTime."',".$section.",'".$email."','".$verificationCode."','unverified')";
    if(mysqli_query($con,$query)){
      echo '<p>Succesfully added</p>';
      if(isset($_POST['mailunp'])){
        $to=$email;
        $subject="ONEC account username and password";
        $msg="Dear ".$fname." ".$lname." below is your username and password for your onec account\nUsername: ".$id."\nPassword: ".$pass;
        if(mail($to,$subject,$msg)){
            echo '<p>email successfully sent</p>';
        }
      }
    }else{
        echo '<p>Succesfully failed</p>';
    }
  }else{
    echo "<p>User name already taken</p>";
  }
}

//Get File Path
function getPath(){
  $fileName=$_FILES['cImage']['name'];
  $fileTmpName=$_FILES['cImage']['tmp_name'];
  $fileSize=$_FILES['cImage']['size'];
  $fileError=$_FILES['cImage']['error'];
  $fileType=$_FILES['cImage']['type'];

  $fileExt=explode('.', $fileName);
  $fileActualExt=strtolower(end($fileExt));
  $allowed=array('jpg','jpeg','png');
  if(in_array($fileActualExt, $allowed)){
      if ($fileError===0){
           if($fileSize<1000000){
                 $fileNameNew=uniqid('',true).'.'.$fileActualExt;
                 $pathFolder='../candidate-image';
                 $fileDestination=$pathFolder.'/'.$fileNameNew;
                 move_uploaded_file($fileTmpName, $fileDestination);
                 return $fileDestination;

           }else{
                 echo '<p>File Size too large!</p>';
           }
     }else{
          echo "<p>Some error occured</p>";
    }

}else{
  echo '<p>file type not allowed</p>';
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
                        <h1 class="page-title">Candidates</h1>
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
                       <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#todo-list">Candidates List</a></li>
                       <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#todo-add">Add Candidates</a></li>
                   </ul>
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
                                        <input type='submit' class="btn btn-primary btn-block"  name='filter' value='Search'>
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
                                            <th>Name</th>
                                            <th>Sex</th>
                                            <th>Section</th>
                                            <th>Email</th>
                                            <th>Verification Code</th>
                                            <th>Verification Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c  WHERE c.sectionId=s.sectionId";
                                      //Filter candidates
                                      if(isset($_POST['filter'])){
                                        if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                                            $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']==="all"){
                                            $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                                            $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                                            $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                                          $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                                          $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
                                        }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                                            $query="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,c.email AS email,c.verificationCode AS vC,c.verificationStatus AS vS,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
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

                                        echo "<tr>
                                                  <td><a href='candidate-detail.php?candidateId=".$candidateId."'>".$row['fname']." ".$row['lname']."</a></td>
                                                  <td><span>".$sex."</span></td>
                                                  <td><span>".$row['section']."</span></td>
                                                  <td><span>".$row['email']."</span></td>
                                                  <td><span>".$row['vC']."</span></td>
                                                  <td><span>".$row['vS']."</span></td></tr>";
                                      }
                                    }else{
                                      echo "<tr><td colspan='9' style='text-align:center;'>No candidate found</td></tr>";
                                    }
                                      ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    </form>
                </div>


                <div class="tab-pane fade" id="todo-add" role="tabpanel">
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Add Candidates</h3>
                          </div>
                          <form  action="<?=$_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data" class="card-body">
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">User Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='id'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='fname'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='lname'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Sex <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <select name='sex' class="form-control show-tick">
                                          <option >Select</option>
                                          <option  value='f'>Female</option>
                                          <option  value='m'>Male</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Section <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                    <select name="section" class="form-control show-tick" >
                                      <option >Select</option>
                                      <?php
                                        $query="SELECT * FROM section";
                                        $result=mysqli_query($con,$query) or die('Error to send query');
                                        if(mysqli_num_rows($result)!=0){
                                          while($row=mysqli_fetch_assoc($result)){
                                            if($row['academicYear']==='1'){
                                              $str="st Year";
                                            }else if($row['academicYear']==='2'){
                                              $str="nd Year";
                                            }else if($row['academicYear']==='3'){
                                              $str="rd Year";
                                            }else{
                                              $str="th Year";
                                            }
                                            echo "<option value='".$row['sectionId']."'>".$row['sectionName']."-".$row['academicYear'].$str." ".$row['department']."</option>";
                                          }
                                        }else{
                                          echo "<option  disabled selected>No section added yet.</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='email' class="form-control"  name='email' placehoolder='example@example.com'>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='password' class="form-control"  name='pass'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Profile Image <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='file' class="form-control"  name='cImage' id='cImage'>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label"></label>
                                  <div class="col-md-7">
                                      <button type="submit" name='add' class="btn btn-primary">Add</button>
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

<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>
</html>
