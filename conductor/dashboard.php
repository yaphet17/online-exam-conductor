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


$query="SELECT username,password FROM conductor WHERE username='".$_SESSION['uname']."' OR email='".$_SESSION['uname']."' LIMIT 1";
$result=mysqli_query($con,$query) or die("Error to send query");
if($row=mysqli_fetch_assoc($result)){
  if(!password_verify($_SESSION['password'],$row['password'])){
    die("Not authorized");
  }
}else{
  die('fatech failed');
}

echo "logged in"?>
