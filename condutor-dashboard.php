<?php
session_start();
if(!isset($_SESSION['uname'])){
  header('Location: conductor-login.php');
}
require_once('config.php');
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
