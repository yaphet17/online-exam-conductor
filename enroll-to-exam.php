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

$candidateId="yaredabate";//$_SESSION['candidateId'];



if(isset($_POST['enroll'])){
  $examCode=$_POST['examCode'];

  //Sanitizing user input
  $examCode=stripcslashes($examCode);

  $query="SELECT COUNT(ee.candidateId) AS ci FROM examination e,examenrollment ee WHERE e.examId=ee.examId and ee.candidateId='".$candidateId."'";
  $result=mysqli_query($con,$query) or die('Error to send query');
  if(mysqli_num_rows($result)!=0){
    $statusQuery="SELECT examStatus AS es FROM examination  WHERE examId=".$examId." LIMIT 1";
    $statusResult=mysqli_query($con,$statusQuery) or die('Error to send query');
    $statusRow=mysqli_fetch_assoc($statusResult);
    if($statusRow['es']==="started"){
      $checkQuery="SELECT examCode AS ei FROM examination WHERE examId='".$examId."' LIMIT 1";
      $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
      $checkRow=mysqli_fetch_assoc($checkResult);
      if($checkRow['ei']===$examCode){
        $updateQuery="UPDATE examenrollment SET attendanceStatus='attending' WHERE candidateId='".$candidateId."'";
        $updateResult=mysqli_query($con,$updateQuery) or die('Error to send query');
        header("Location: exam.php?examId=".$examId);
      }else{
        echo "<p>Invalid exam code</p>";
      }
    }else{
      if($statusRow['es']==='created'){
        echo "<p>The exam doesn't start yet</p>";
      }else if($statusRow['es']==='canceled'){
          echo "<p>The has been canceled</p>";
      }else{
        echo "<p>The exam has been completed</p>";
      }

    }
  }else{
    echo "<p>You are not eligible to take the exam</p>";
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
