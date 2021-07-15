<?php
session_start();
require('config.php');

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <table>
    <th>Name</th>
    <th>Sex</th>
    <th>Registration Date</th>
    <th>Section</th>
    <th>Email</th>
    <th>Verification Code</th>
    <th>Verification Status</th>
  <?php
  $query="SELECT * FROM candidate c,section s WHERE c.sectionId=s.sectionId";
  $result=mysqli_query($con,$query) or die('Error to send query');
  if(mysqli_num_rows($result)!=0){
  while($row=mysqli_fetch_assoc($result)){
    echo "<tr><td><a href='candidate-detail.php?candidateId=".$row['candidateId']."'>".$row['firstName']." ".$row['lastName']."</a></td>
              <td>".$row['sex']."</td>
              <td>".$row['registrationDate']."</td>
              <td>".$row['sectionName']."</td>
              <td>".$row['email']."</td>
              <td>".$row['verificationCode']."</td>
              <td>".$row['verificationStatus']."</td></tr>";
  }
}else{
  echo "<tr><td colspan='7' style='text-align:center;'>No candidate found.</td></tr>";
}
?>
</table>
</body>
</html>
