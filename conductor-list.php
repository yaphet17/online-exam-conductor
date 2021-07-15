<?php
session_start();
require('config.php');

//Delete selected conductor from db
if(isset($_POST['delete'])){
  $conductorQuery="SELECT username FROM conductor";
  $conductorResult=mysqli_query($con,$conductorQuery) or die('Error to send query');
  while($conductorRow=mysqli_fetch_assoc($conductorResult)){
    $conductorId=$conductorRow['username'];
    if($_POST[$conductorId]){
      if(!empty($_POS[  $conductorId])){
        $deleteQuery="DELETE FROM conductor WHERE username='".$conductorId."'";
        mysqli_query($deleteQuery) or die('Error to send query');
      }
    }

  }
}

?>
<html>
<head></head>
<body>
  <p>Conductors</p>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
    <table>
      <th></th>
      <th>Full Name</th>
      <th>User Name</th>
      <th>Role</th>
      <th>Email</th>
      <th>Action</th>
      <?php
      $query="SELECT username,prefix,firstName,lastName,role ,email FROM conductor ORDER BY firstName,lastName";
      $result=mysqli_query($con,$query) or die('Error to send query');
      if(mysqli_num_rows($result)){
        while($row=mysqli_fetch_assoc($result)){
         $conductorId=$row['username'];
         echo "<tr><td><input type='checkbox' name='".$conductorId."'></td>
              <td><a href='conductor-detail.php?conductorId=".$conductorId."'>".$row['prefix']." ".$row['firstName']." ".$row['lastName']."</a></td>
              <td>".$conductorId."</td>
              <td>".$row['role']."</td>
              <td>".$row['email']."</td>
              <td><a href='delete-conductor.php?conductorId=".$conductorId."'>Delete</td></tr>";
        }
      }else{
        echo "<tr><td colspan='5' style='text-align:center;'>No conductor found.</td></tr>";
      }
      ?>
    </table>
    <input type='submit' name='delete' value='Delete'>
  </form>
</body>
</html>
