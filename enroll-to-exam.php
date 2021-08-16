<?php
session_start();
require('config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='a8226c2'){
    session_destroy();
    header('Location: index.php');
  }
}

//Copy url value
if(isset($_GET['examId'])){
  $examId=htmlspecialchars($_GET['examId']);
}else{
  //Alternate option if a page is refreshed by submit button
  if(isset($_POST['examId'])){
    $examId=$_POST['examId'];
  }else{
    die('exam id not found');
  }
}

$candidateId=$_SESSION['uname'];



?>
<!DOCTYPE html>

<html>

<head lang="en">
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/index.css" >
</head>


<body>
    <header>
        <div class='header'>
            <!--Header Marker-->
        </div>
    </header>
    <div class="main_container">
        <div class="signForm">
            <form action="<?=$_SERVER['PHP_SELF']?>" method='POST' >
                    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
                    <span>
                        <p class='signLabel'>Enroll to exam</p>
                    </span>
                    <span >
                      <?php

                      if(isset($_POST['enroll'])){
                        $examCode=$_POST['examCode'];

                        //Sanitizing user input
                        $examCode=stripcslashes($examCode);

                        $query="SELECT ee.attendanceStatus FROM examination e,examenrollment ee WHERE e.examId=ee.examId and ee.candidateId='".$candidateId."'";
                        $result=mysqli_query($con,$query) or die('Error to send query');
                        if(mysqli_num_rows($result)!=0){
                          $row=mysqli_fetch_assoc($result) or die('Erro to fetch query!');
                          $statusQuery="SELECT examStatus AS es,examDuration AS ed FROM examination WHERE examId=".$examId;
                          $statusResult=mysqli_query($con,$statusQuery) or die('Error to send querry');
                          $statusRow=mysqli_fetch_assoc($statusResult);
                          if($row['attendanceStatus']==='notattended' or $row['attendanceStatus']==='attending' ){
                          if($statusRow['es']==="started"){
                              $checkQuery="SELECT examCode AS ei FROM examination WHERE examId=".$examId;
                              $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
                              $checkRow=mysqli_fetch_assoc($checkResult);
                              $preventQuery="SELECT * FROM answer WHERE examId=".$examId." AND candidateId='".$candidateId."'";
                              if($checkRow['ei']===$examCode){
                                $preventResult=mysqli_query($con,$preventQuery) or die('Error to send query');
                                if(mysqli_num_rows($preventResult)==0){
                                  $instanceQuery="SELECT * FROM examtoken WHERE candidateId='".$candidateId."' AND examId=".$examId;
                                  $instanceResult=mysqli_query($con,$instanceQuery) or die('Error to send query');
                                  if(mysqli_num_rows($instanceResult)===0){
                                      $token=bin2hex(openssl_random_pseudo_bytes(7));
                                      $insertInstQuery="INSERT INTO examtoken(candidateId,examId,token) VALUES('".$candidateId."',".$examId.",'".$token."')";
                                      mysqli_query($con,$insertInstQuery) or die('Error to send query');
                                      $_SESSION['token']=$token;
                                      $updateQuery="UPDATE examenrollment SET attendanceStatus='attending' WHERE candidateId='".$candidateId."'";
                                      $updateResult=mysqli_query($con,$updateQuery) or die('Error to send query');
                                      $duration=$statusRow['ed'];
                                      $_SESSION['duration']=$duration;
                                      $_SESSIOn['start_time']=date('Y-m-d H:i:s');
                                      $end_time=date('Y-m-d H:i:s',strtotime('+'.$_SESSION['duration'].'minutes',strtotime($_SESSIOn['start_time'])));
                                      $_SESSION['end_time']=$end_time;
                                   header("Location: exam.php?examId=".$examId);
                                }else{
                                    echo "<p style='color:rgb(255,0,0);'>Another instance is already created.</p>";
                                }
                                }else{
                                  $redirect="Location: exam-result.php?examId=".$examId;
                                  header($redirect);
                                }
                              }else{
                                  echo "<p style='color:rgb(255,0,0);'>Invalid exam code</p>";
                             }
                          }else{
                            if($statusRow['es']==='created'){
                              echo "<p style='color:rgb(255,0,0);'>The exam doesn't start yet</p>";
                            }else if($statusRow['es']==='canceled'){
                                echo "<p style='color:rgb(255,0,0);'>The has been canceled</p>";
                            }else{
                              echo "<p style='color:rbg(255,0,0);'>The exam has been completed</p>";
                            }
                          }
                        }else{
                            echo "<p style='color:rgb(255,0,0);'>You have already taken the exam.</p>";
                        }
                        }else{
                          echo "<p style='color:rgb(255,0,0);'>You are not eligible to take the exam</p>";
                        }

                      }
                       ?>
                    </span>
                    <input type="text" class="signField" name='examCode' placeholder="Exam Code" required>
                    <span><input type="submit"  class="submitSign" name='enroll' value="Enroll" ></span>
                    <span class='submitSign' style='display:flex;justify-content:flex-end;background-color:inherit;padding-right:5px;'><a href='dashboard.php'>Back to home</a></span>
            </form>
        </div>
    </div>
</body>

</html>
