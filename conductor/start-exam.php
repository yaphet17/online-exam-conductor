<?php
session_start();
require('../config.php');

$conductorId=$_SESSION['conductorId'];
$pass=$_SESSION['pass'];
if(!isset($conductorId) or !isset($pass) or !isset($_SESSION['level']) or empty($conductorId) or empty($pass) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}

if(!empty($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{
  die("exam id not found");
}

$query="UPDATE examination SET examStatus='started' WHERE examId=".$examId;
if(mysqli_query($con,$query)){
  header('Location: exam-list.php');
}else{
  die('Failed to start the exam');
}
?>
