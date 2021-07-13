<?php
session_start();
require_once('config.php');

$candidateId="yaredabate";//$_SESSION['candidateId'];

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <table>
    <th>Candidate</th>
    <th>Exam Title</th>
    <th>Obtained Mark</th>
    <th>Maximum Mark</th>
  <?php
  $resultQuery="SELECT m.maximumMark AS mm,m.obtainedMark AS om,e.examTitle AS et,c.firstName AS fn,c.lastName AS lsn FROM marK m,examination e,candidate c WHERE m.candidateId='".$candidateId."' AND m.candidateId=c.candidateId AND m.examId=e.examId";
  $resultResult=mysqli_query($con,$resultQuery) or die('Error to send query');
  while($resultRow=mysqli_fetch_assoc($resultResult)){
    echo "<tr><td>".$resultRow['fn']." ".$resultRow['lsn']."</td><td>".$resultRow['et']."</td><td>".$resultRow['om']."</td><td>".$resultRow['mm']."</td></tr>";
  }

  ?>
  </table>
</body>
</html>
