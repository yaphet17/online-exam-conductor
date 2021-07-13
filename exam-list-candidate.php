<?php
session_start();
require_once('config.php');

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <p>Exam List</p>
  <table>
    <th>Title</th>
    <th>Exam Date and Time</th>
    <th>Exam Duration</th>
    <th>Total Question</th>
    <th>Conductor</th>
    <th>Exam Status</th>
    <th>Enrollment</th>
    <?php
    $query="SELECT e.examId AS ei,e.examTitle AS et,e.examDateTime AS edt,e.examDuration AS ed,e.totalQuestion AS tq,c.prefix AS p,c.firstName AS f,c.lastName AS l,e.examStatus AS es FROM examination e,conductor c WHERE e.conductorId=c.username ORDER BY examDateTime DESC,examStatus DESC";
    $result=mysqli_query($con,$query) or die('Error to send query');
    while($row=mysqli_fetch_assoc($result)){
      if(0!=0){//$row['es']!='started'
        $str='#';
      }else{
        $str="enroll-to-exam.php?examId=".$row['ei'];
      }
      echo "<tr><td>".$row['et']."</td><td>".$row['edt']."</td><td>".$row['ed']."</td><td>".$row['tq']."</td><td>".$row['p']." ".$row['f']." ".$row['l']."</td><td>".$row['es']."</td><td><a href='".$str."'>Enroll</a></td></tr>";
    }
    ?>
  </table>

</body>
</html>
