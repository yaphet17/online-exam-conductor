<?php
session_start();
require('../config.php');

if(isset($_GET['conductorId'])){
  if(!empty($_GET['conductorId'])){
    $conductorId=htmlentities($_GET['conductorId']);
  }else{
    die('conductor id not found');
  }
}else{
  die('conductor id not found');
}

?>
<html>
<head></head>
<body>
  <p>Conductor details</p>
<?php
$query="SELECT* FROM conductor WHERE username='".$conductorId."'";
$result=mysqli_query($con,$query) or die('Error to send query');
while($row=mysqli_fetch_assoc($result)){
  echo "<p>Name: ".$row['prefix']." ".$row['firstName']." ".$row['lastName']."</p>
        <p>Role: ".$row['role']."</p>
        <p>Email: ".$row['email']."</p>
        <p>Verification Code: ".$row['verificationCode']."</p>
        <p>Verification Status: ".$row['verificationStatus']."</p>";
}
?>
<p>Exam conducted</p>
<table>
  <th>Exam Title</th>
  <th>Creation Date</th>
  <th>Starting Time</th>
  <th>Duration</th>
  <th>Status</th>
<?php
$examQuery="SELECT examId,examTitle,examCreationDate,examDateTime,examDuration,examStatus FROM examination WHERE conductorId='".$conductorId."'";
$examResult=mysqli_query($con,$examQuery) or die('Error to send query');
if(mysqli_num_rows($examResult)){
  while($examRow=mysqli_fetch_assoc($examResult){
    echo "<tr><td><a href='exam-detal-admin.php?examId=".$examRow['examRow']."'>".$examRow['examTitle']."</a></td>
          <td>".$examRow['examCreationDate']."</td>
          <td>".$examrow['examDateTime']."</td>
          <td>".$examRow['examDuration']."</td>
          <td>".$examRow['examStatus']."</td></tr>";
  }
}else{
  echo "<tr><td colspan='5'>No exam found.</td></tr>";

}
?>
</html>
