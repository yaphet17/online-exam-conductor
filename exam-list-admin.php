<?php
session_start();
require_once('config.php');



//Start selected exams
if(isset($_POST['start'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='started' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}
//Suspend selected exams
if(isset($_POST['suspend'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='suspended' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}

//Unsuspend selected exams
if(isset($_POST['unsuspend'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='suspend'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='created' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}
//Cancel selected exams
if(isset($_POST['cancel'])){
  $getSQuery="SELECT examId AS ei,examStatus AS es FROM examination";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($eRow=mysqli_fetch_assoc($getSResult)) {
    if($eRow['es']==='created' or  $eRow['es']==='suspended'){
      if(isset($_POST[$eRow['ei']]) and !empty($_POST[$eRow['ei']])){
        $setQuery="UPDATE examination SET examStatus='created' WHERE examId=".$eRow['ei'];
        mysqli_query($con,$setQuery) or die('Error to send querry');
      }
    }
  }
}



?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
  <p>Exams</p>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
  <table>
    <th></th>
    <th>Exam Name</th>
    <th>Creation Date</th>
    <th>Starting DateTime</th>
    <th>Duration</th>
    <th>Exam Code</th>
    <th>Status</th>
    <th>Action</th>
  <?php
  $examQuery="SELECT * FROM examination ORDER BY examCreationDate DESC,examDateTime DESC";
  $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
  $i=1;
  while($examRow=mysqli_fetch_assoc($examResult)){
    $str1='#';
    $str2='#';
    $str3='#';
    $examId=$examRow['examId'];
    if($examRow['examStatus']==='created'){
      $str1="start-exam.php?examId=".$examId;
      $str2="suspend-exam.php?examId=".$examId;
    }
    if($examRow['examStatus']==='suspended'){
      $str3="unsuspend-exam.php?examId=".$examId;
    }

    echo "<tr><td><input type='checkbox' id='".$i."' name='".$examId."'></td><td><a href='exam-detail-conductor.php?examId=".$examRow['examId']."'>".$examRow['examTitle']."</td><td>".$examRow['examCreationDate']."</td><td>".$examRow['examDateTime']."</td><td>".$examRow['examDuration']."</td><td>".$examRow['examCode']."</td><td>".$examRow['examStatus']."</td><td><a href='".$str3."'>Start</a><i style='color:blue'>|</i><a href='".$str2."'>Suspend</a><i style='color:blue'>|</i><a href='".$str3."'>Unsuspend</a><i style='color:blue'>|</i><a href='cancel-exam.php?examId=".$examId."'>Cancel</a></td></tr>";
    $i++;
  }
  ?>
</table>
<input type='submit' name='start' value='Start'>
<input type='submit' name='suspend' value='Suspend'>
<input type='submit' name='suspend' value='Unsuspend'>
<input type='submit' name='cancel' value='Cancel'>
</form>
</body>
</html>
