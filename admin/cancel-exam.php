<?php
session_start();
require('../config.php');

if(!empty($_GET['examId'])){
  $examId=$_GET['examId'];
}else{
  die("exam id not found");
}
$query="UPDATE examination SET examStatus='canceled' WHERE examId=".$examId;
if(mysqli_query($con,$query)){
  header('Location: exam-list.php');
}else{
  die('Failed to cancel the exam');
}
?>
