<?php
session_start();
require('../config.php');

//Delete selected candidates
if(isset($_POST['delete'])){
  $candidateQuery="SELECT candidateId AS ci FROM candidate";
  $candidateResult=mysqli_query($con,$candidateQuery) or die('Error to send query');
  while($candidateRow=mysqli_fetch_assoc($candidateResult)){
    if(isset($_POST[$candidateRow['ci']])){
      if(!empty($_POST[$candidateRow['ci']])){
        $deleteQuery="DELETE FROM candidate WHERE candidateId='".$candidateRow['ci']."'";
        mysqli_query($con,$deleteQuery) or die('Error to send query');
      }
    }

  }
}?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
  <table>
    <th></th>
    <th>Name</th>
    <th>Sex</th>
    <th>Registration Date</th>
    <th>Section</th>
    <th>Email</th>
    <th>Verification Code</th>
    <th>Verification Status</th>
    <th>Action</th>
  <?php
  $query="SELECT * FROM candidate c,section s WHERE c.sectionId=s.sectionId";
  $result=mysqli_query($con,$query) or die('Error to send query');
  if(mysqli_num_rows($result)!=0){
  while($row=mysqli_fetch_assoc($result)){
    $candidateId=$row['candidateId'];
    echo "<tr><td><input type='checkbox' name='".$candidateId."'></td>
              <td><a href='candidate-detail.php?candidateId=".$candidateId."'>".$row['firstName']." ".$row['lastName']."</a></td>
              <td>".$row['sex']."</td>
              <td>".$row['registrationDate']."</td>
              <td>".$row['sectionName']."</td>
              <td>".$row['email']."</td>
              <td>".$row['verificationCode']."</td>
              <td>".$row['verificationStatus']."</td>
              <td><a href='delete-candidate.php?candidateId=".$candidateId."'>Delete</a></td></tr>";
  }
}else{
  echo "<tr><td colspan='9' style='text-align:center;'>No candidate found</td></tr>";
}
?>
</table>
<input type='submit' name='delete' value='Delete'>
</form>
</body>
</html>
