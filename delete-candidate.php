<?php
session_start();
require('config.php');


//Delete candidate from db
if(isset($_GET['candidateId'])){
  if(!empty($_GET['candidateId'])){
    $candidateId=htmlentities($_GET['candidateId']);
    $query="DELETE FROM candidate WHERE candidateid='".$candidateId."'";
    mysqli_query($con,$query) or die('Error to send query');
    header('Location: candidate-list-admin.php');
  }else{
    die('candidate id not found');
  }
}else{
    die('candidate id not found');
}

?>
