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

//Delete candidate from db
if(isset($_GET['candidateId'])){
  if(!empty($_GET['candidateId'])){
    $candidateId=htmlentities($_GET['candidateId']);
    $query="DELETE FROM candidate WHERE candidateid='".$candidateId."'";
    mysqli_query($con,$query) or die('Error to send query');
    header('Location: candidate-list.php');
  }else{
    die('candidate id not found');
  }
}else{
    die('candidate id not found');
}

?>
