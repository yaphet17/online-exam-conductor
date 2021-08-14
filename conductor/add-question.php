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


if(isset($_POST['add'])){
  $tqQuery="SELECT totalQuestion FROM examination WHERE examId=".$examId;
  $tqResult=mysqli_query($con,$tqQuery) or die('Error to send query');
  if($tqRow=mysqli_fetch_assoc($tqResult)){
  for($j=1;$j<=$tqRow['totalQuestion'];$j++){
  $qt="questionTitle".$j;
  $op="answerOption".$j;
  $questionTitle=$_POST[$qt];
  $option=$_POST[$op];

  //Sanitizing user input
  $questionTitle=stripcslashes($questionTitle);
  $option=stripcslashes($option);

  $columns="examId,questionTitle,answerOption";

  //Inseting user input to db
  $query="INSERT INTO question (".$columns.") VALUES(".$examId.",'".$questionTitle."','".$option."')";
  if(mysqli_query($con,$query)){
    $query="SELECT questionId AS qi FROM question WHERE questionTitle='".$questionTitle."'";
    $result=mysqli_query($con,$query) or die('Error to send query');
    if($row=mysqli_fetch_assoc($result)){
      $columns="questionId,optionNumber,optionTitle";
      $ot="option1-".$j;
      $optionTitle=$_POST[$ot];
      $optionTitle=stripcslashes($optionTitle);
      $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",1,'".$optionTitle."')";
      if(mysqli_query($con,$query)){
      }else{
          echo "<p>Failed to add  option 1</p>";
      }
      $ot="option2-".$j;
      $optionTitle=$_POST[$ot];
      $optionTitle=stripcslashes($optionTitle);
      $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",2,'".$optionTitle."')";
      if(mysqli_query($con,$query)){
      }else{
          echo "<p>Failed to add  option 2</p>";
      }
      $ot="option3-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",3,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
          }else{
            echo "<p>Failed to add  option 3</p>";
          }
      }
      $ot="option4-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",4,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
          }else{
            echo "<p>Failed to add  option 4</p>";
          }
      }
      $ot="option5-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",5,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
          }else{
            echo "<p>Failed to add  option 5</p>";
          }
      }
    }
  }else{
    echo "<p>Failed to add question</p>";
  }
}
}
}

//Redirect to exam list page
if(isset($_POST['done'])){
  header('Location: add-to-exam.php?examId=".$examId."');
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

    <div class="page" style='margin:0;left:0;right:0;width:100%;'>
        <div id="page_top" class="section-body top_dark">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <h1 class="page-title">Add questions to exam</h1>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> <!--Notification--></h3>
                        </div>
                        <form  action="<?=$_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data" class="card-body">
                            <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlentities($_GET['examId']);}?>">
                            <div class="form-group row">
                              <?php
                              //Get total question
                              $tqQuery="SELECT totalQuestion FROM examination WHERE examId=".$examId;
                              $tqResult=mysqli_query($con,$tqQuery) or die('Error to send query');
                              if($tqRow=mysqli_fetch_assoc($tqResult)){
                                for($i=1;$i<=$tqRow['totalQuestion'];$i++){
                                  echo"<div class='form-group row'> <textarea type='textarea' class='form-control' name='questionTitle".$i."' placeholder='Enter question ".$i."'></textarea>
                                    <input type='text' class='form-control' name='option1-".$i."' placeholder='option 1'>
                                    <input type='text' class='form-control' name='option2-".$i."' placeholder='option 2'>
                                    <input type='text' class='form-control' name='option3-".$i."' placeholder='option 3'>
                                    <input type='text' class='form-control' name='option4-".$i."' placeholder='option 4'>
                                    <input type='text' class='form-control' name='option5-".$i."' placeholder='option 5'>
                                    <select name='answerOption".$i."' class='form-control show-tick' style='width:200px'>
                                      <option disabled selected hidden>Answer Option</option>
                                      <option value='option1'>Option 1</option>
                                      <option value='option2'>Option 2</option>
                                      <option value='option3'>Option 3</option>
                                      <option value='option4'>Option 4</option>
                                      <option value='option5'>Option 5</option>
                                    </select></div>";
                                }
                              }else{
                                echo "<p>Failed to fetch result</p>";
                              }
                              ?>

                              <div class="card" style='margin-top:20px;'>
                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <button type="submit" name='add' class="btn btn-primary">Add</button>
                                          <button type="submit" name='done' class="btn btn-primary">Done</button>
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
</div>


<script src="../assets/bundles/lib.vendor.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>
</html>
