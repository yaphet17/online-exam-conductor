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


if(isset($_GET['examId']) AND !empty($_GET['examId'])){
  $examId=htmlspecialchars($_GET['examId']);
}else{
  die('exam id not found');
}

?>
<!DOCTYPE html>

<html>
<head>
</head>
<body>

  <?php
  echo "<a href='exam-report.php?examId=".$examId."'><button>Exam Report</button></a>";
  $query="SELECT * FROM examination e,conductor c WHERE  e.examId=".$examId;
  $result=mysqli_query($con,$query) or die('Error to send query');
  $row=mysqli_fetch_array($result) or die('Error to fetch query');
  echo "<p>Exam Title: ".$row['examTitle']."</p>
       <p>Conducted By: ".$row['prefix']." ".$row['firstName']." ".$row['lastName']."</p>
       <p>Creation On: ".$row['examCreationDate']."</p>
       <p>Starts in: ".$row['examDateTime']."</p>
       <p>Duration: ".$row['examDuration']."</p>
       <p>Total Question: ".$row['totalQuestion']."</p>
       <p>Marks Per Right Answer: ".$row['marksPerRightAnswer']."</p>
       <p>Marks Per Wrong Answer: ".$row['marksPerWrongAnswer']."</p>
       <p>Code: ".$row['examCode']."</p>
       <p>Status: ".$row['examStatus']."</p><br>";
       $examStatus=false;
       if($row['examStatus']==='completed'){
         $examStatus=true;
       }
  ?>
  <p>Candidates enrolled to the exam</p>
  <table>
    <th>Name</th>
    <th>Section</th>
    <th>Department</th>
    <th>Academic Year</th>
    <th>Attendance Status</th>
    <th>Result</th>
  <?php
  $candidateQuery="SELECT * FROM candidate c,examenrollment e WHERE c.candidateId=e.candidateId AND e.examId=".$examId." ORDER BY c.firstName,c.lastName,c.registrationDate DESC";
  $candidateResult=mysqli_query($con,$candidateQuery);
  while($candidateRow=mysqli_fetch_assoc($candidateResult)){
    //Fetch section data
    $sectionQuery="SELECT * FROM section WHERE sectionId='".$candidateRow['sectionId']."'";
    $sectionResult=mysqli_query($con,$sectionQuery) or die('Error to send query');
    $sectionRow=mysqli_fetch_assoc($sectionResult) or die('Error to fetch query');
    //Fetch candidates exam result
    echo "<tr><td><a href='candidate-detail.php?candidateId=".$candidateRow['candidateId']."'>".$candidateRow['firstName']." ".$candidateRow['lastName']."</a></td>
              <td>".$sectionRow['sectionName']."</td>
              <td>".$sectionRow['department']."</td>
              <td>".$sectionRow['academicYear']."</td>";
    if($examStatus){
      $markQuery="SELECT maximumMark AS mm,obtainedMark AS om FROM mark WHERE candidateId='".$candidateRow['candidateId']."' AND examId=".$examId;
      $markResult=mysqli_query($con,$markQuery) or die('Error to send querry');
      if(mysqli_num_rows($markResult)!=0){
        $markRow=mysqli_fetch_assoc($markResult) or die('Error to fetch query');
        echo "<td>".$candidateRow['attendanceStatus']."</td>
            <td>".$markRow['om']."/".$markRow['mm']."</td></tr>";
      }else{
        echo "<td>none</td>
              <td>none</td></tr>";
      }
    }else{
      echo "<td>none</td>
            <td>none</td></tr>";
    }

  }
  ?>

</table>
</body>
</html>
