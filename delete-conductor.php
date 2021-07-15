<?php
session_start();
require('config.php');


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
