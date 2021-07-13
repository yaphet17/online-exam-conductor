<?php
session_start();
require_once('config.php');

if(!empty($_GET['examId'])){
  $examId=$_GET['examId'];
}else{
  die("exam id not found");
}
$query="UPDATE examination SET examStatus='suspended' WHERE examId=".$examId;
if(mysqli_query($con,$query)){
  header('Location: exams-conductor.php');
}else{
  die('Failed to suspend the exam');
}
?>
