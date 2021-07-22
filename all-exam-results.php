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
