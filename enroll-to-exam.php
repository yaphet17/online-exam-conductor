<?php
session_start();
require_once('config.php');

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

$candidateId="yafet123";//$_SESSION['candidateId'];



if(isset($_POST['enroll'])){
  $examCode=$_POST['examCode'];

  //Sanitizing user input
  $examCode=stripcslashes($examCode);

  $query="SELECT COUNT(ee.candidateId) AS ci FROM examination e,examenrollment ee WHERE e.examId=ee.examId and ee.candidateId='".$candidateId."'";
  $result=mysqli_query($con,$query) or die('Error to send query');
  if(mysqli_num_rows($result)!=0){
    $checkQuery="SELECT examCode AS ei FROM examination WHERE examId='".$examId."' LIMIT 1";
    $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
    $checkRow=mysqli_fetch_assoc($checkResult);
    if($checkRow['ei']===$examCode){
      $updateQuery="UPDATE examenrollment SET attendanceStatus='attending'";
      $updateResult=mysqli_query($con,$updateQuery) or die('Error to send query');
      header("Location: exam.php");
    }else{
      echo "Invalid exam code";
    }
  }else{
    echo "You are not eligible to take the exam";
  }
}
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
    <input type='text' name='examCode' placeholder='Exam Code'>
    <input type='submit' name='enroll' value='Enroll'>
  </form>
</body>
</html>
