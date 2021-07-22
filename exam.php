<?php
session_start();
require('config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='a8226c2'){
    session_destroy();
    header('Location: index.php');
  }
}

$candidateId="yaredabate";//$_SESSION['candidateId'];

//Copy url value
if(isset($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{ //Alternate option if a page is refreshed by submit button
  if(isset($_POST['examId'])){
    $examId=$_POST['examId'];
  }else{
    die('exam id not found');
  }
}

//Submit candidate answer to db
if(isset($_POST['submit'])){
$preventQuery="SELECT COUNT(*) AS c FROM answer WHERE examId=".$examId." AND candidateId='".$candidateId."'";
$preventResult=mysqli_query($con,$preventQuery) or die('Error to send querrry');
$preventRow=mysqli_fetch_assoc($preventResult);
if($preventRow['c']==='0'){
$markQuery="SELECT totalQuestion AS tq,marksPerRightAnswer AS mra,marksPerWrongAnswer AS mwa FROM examination WHERE examId=".$examId." LIMIT 1";
$markResult=mysqli_query($con,$markQuery) or die('Error to send query');
$markRow=mysqli_fetch_assoc($markResult) or die('Error to fetch query');
for($j=1;$j<=$markRow['tq'];$j++){
  $str="question".$j;
  $valArr=explode('-',$_POST[$str]);
  $questionId=$valArr[0];
  $option=$valArr[1];
  //Check if the answer to the question is correct or not
  $checkQuery="SELECT answerOption AS ao FROM question WHERE questionId=".$questionId." LIMIT 1";
  $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
  $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch query');
  $answer=$checkRow['ao'];
  if($answer===$option){
    $status="right";
  }else{
    $status="wrong";
  }
  $answerQuery="INSERT INTO answer (examid,candidateId,questionId,answerOPtion,status) VALUES(".$examId.",'".$candidateId."',".$questionId.",'".$option."','".$status."')";
  mysqli_query($con,$answerQuery) or die('Error to send query');
}
$statusQuery="UPDATE examenrollment SET attendanceStatus='attended' WHERE candidateId='".$candidateId."' AND examId=".$examId;
mysqli_query($con,$statusQuery) or die('Error to send query');
header("Location: exam-result-candidate.php?examId=".$examId);
}else{
  die('You have already submitted your answer');
}
}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
  <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
  <?php
  $examQuery="SELECT examTitle AS et FROM examination WHERE examId=".$examId." LIMIT 1";
  $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
  $examRow=mysqli_fetch_assoc($examResult);
  echo "<p>".$examRow['et']."</p></br>";
  $questionQuery="SELECT * FROM question WHERE examId=".$examId;
  $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
  $i=1;
  while($questionRow=mysqli_fetch_assoc($questionResult)){
    $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
    $optionResult=mysqli_query($con,$optionQuery) or die('Error to send querry');
    echo "<p><strong>".$i."</strong> ".$questionRow['questionTitle']."</p>";
    $choice=65;
    while($optionRow=mysqli_fetch_assoc($optionResult)){
      echo "<label><strong>".chr($choice++)."</strong><input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."'>".$optionRow['optionTitle']."</label></br>";
    }
    $i++;
  }
  ?>
  <input type='submit' name='submit' value='Submit'>
</form>
</body>
</html>
