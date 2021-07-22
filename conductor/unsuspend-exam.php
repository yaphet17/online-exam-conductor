<?php
session_start();
require('../config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}

if(!empty($_GET['examId'])){
  $examId=$_GET['examId'];
}else{
  die("exam id not found");
}
$query="UPDATE examination SET examStatus='created' WHERE examId=".$examId;
if(mysqli_query($con,$query)){
  header('Location: exam-list.php');
}else{
  die('Failed to unsuspend the exam');
}
?>
