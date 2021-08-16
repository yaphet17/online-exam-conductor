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
$examId=$_SESSION['examId'];

$query="UPDATE examenrollment SET attendanceStatus='dispelled' WHERE candidateId='".$candidateId."' AND examId=".$examId;
if(mysqli_query($con,$query)){
  deleteToken($candidateId,$examId,$con);
  header('Location: dashboard.php');
}else{
  die('Failed to dispell candidate');
}

function deleteToken($candidateId,$examId,$con){
  $tokenQuery="DELETE FROM examtoken WHERE candidateId='".$candidateId."' AND examId=".$examId;
  mysqli_query($con,$tokenQuery) or die('Error to send query');
  unset($_SESSION['token']);
}

?>
