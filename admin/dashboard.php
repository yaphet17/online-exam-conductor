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

$query="SELECT username,password FROM administrator WHERE username='".$_SESSION['uname']."' LIMIT 1";
$result=mysqli_query($con,$query) or die("Can't execute query");
if($row=mysqli_fetch_assoc($result)){
  if(!password_verify($_SESSION['pass'],$row['password'])){
    session_destroy();
    die("Not authorized");
  }
}else{
  die('fatech failed');
}

echo "logged in"?>
