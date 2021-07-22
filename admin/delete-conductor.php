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

//Delete conductor from db
if(isset($_GET['conductorId'])){
  if(!empty($_GET['conductorId'])){
    $conductorId=htmlentities($_GET['conductorId']);
    $query="DELETE FROM conductor WHERE username='".$conductorId."'";
    mysqli_query($con,$query) or die('Error to send query');
    header('Location: conductor-list.php');
  }else{
    die('conductor id not found');
  }
}else{
    die('conductor id not found');
}

?>
