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

//Inserting user input to the db
if(isset($_POST['add'])){
  $sectionName=$_POST['sectionName'];
  $academicYear=$_POST['academicYear'];
  $department=$_POST['department'];

  //Sanitizing user input
  $sectionName=stripcslashes($sectionName);
  $department=stripcslashes($department);

  $query="INSERT INTO section (sectionName,academicYear,department) VALUES('".$sectionName."',".$academicYear.",'".$department."')";
  if(mysqli_query($con,$query)){
    echo "<p>Succesfully added</p>";
  }else{
    echo "<p>Failed to send</p";
  }
}
?>
<!doctype html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
    <input type='text' name='sectionName' placeholder='Section Name'>
    <select name='academicYear'>
      <option selected disabled hidden>Academic Year</option>
      <option value='1'>1st</option>
      <option value='2'>2nd</option>
      <option value='3'>3rd</option>
      <option value='4'>4th</option>
      <option value='5'>5th</option>
  </select>
  <input type='text' name='department' placeholder="Department">
  <input type='submit' name='add' value='Add'>
  </form>
</body>
</html>
