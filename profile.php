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
$candidateId=$_SESSION['uname'];



//Fetching candidate's information from db
$query="SELECT * FROM candidate c,section s WHERE c.sectionId=s.sectionId AND c.candidateId='".$candidateId."'";
$result=mysqli_query($con,$query) or die('Error to send query');
$row=mysqli_fetch_assoc($result) or die('Error to fetch query');
$imagePath=$row['candidateImage'];
$fname=$row['firstName'];
$lname=$row['lastName'];
$sex=$row['sex'];
$regDate=$row['registrationDate'];
$section=$row['sectionName'];
$email=$row['email'];
$vCode=$row['verificationCode'];
$vStatus=$row['verificationStatus'];

?>
