<?php
session_start();
require('config.php');

//Get user level
$userLevel="conductor";//$_SESSION['userLevel'];

if(isset($_GET['candidateId']) and !empty($_GET['candidateId'])){
  $candidateId=htmlentities($_GET['candidateId']);
}else{
  die('candidate id not found!');
}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <?php
    $query="SELECT * FROM candidate c,section s WHERE c.sectionId=s.sectionId AND c.candidateId='".$candidateId."'";
    $result=mysqli_query($con,$query) or die('Error to send query');
    $row=mysqli_fetch_assoc($result) or die('Error to fetch query');
    echo"<img src='".$row['candidateImage']."' style='width:200px;height:200px;'>
         <p>First Name: ".$row['firstName']."</p>
         <p>First Name: ".$row['lastName']."</p>
         <p>Sex: ".$row['sex']."</p>
         <p>Registration Date: ".$row['registrationDate']."</p>
         <p>Section: ".$row['sectionName']."</p>
         <p>Email: ".$row['email']."</p>
         <p>Verification Code: ".$row['verificationCode']."</p>
         <p>Verification Status: ".$row['verificationStatus']."</p>";
  ?>
  <p>Exams Taken</p>
  <table>
    <th>Exam Title</th>
    <th>Exam Status</th>
    <th>Attendance Status</th>
    <th>Maximum Mark</th>
    <th>Obtained Mark</th>
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

    echo "<tr><td><a href='exam-detail-".$userLevel.".php?examId=".$examId."'>".$examRow['examTitle']."</a></td>
        <td>".$examRow['examStatus']."</td>
        <td>".$status."</td>
        <td>".$examRow['marksPerRightAnswer']*$examRow['totalQuestion']."</td>
        <td>".$mark."</td>
        </tr>";

  }

  ?>
</table>
</body>
</html>
