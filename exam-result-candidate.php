<?php
session_start();
require_once('config.php');

$candidateId="yaredabate";//$_SESSION['candidateId'];
$examId=$_GET['examId'];
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <?php
  $examQuery="SELECT examTitle AS et,totalQuestion AS tq,marksPerRightAnswer AS mra,marksPerWrongAnswer AS mwa FROM examination WHERE examId=".$examId." LIMIT 1";
  $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
  $examRow=mysqli_fetch_assoc($examResult);
  echo "<p>".$examRow['et']."</p></br>";
  $questionQuery="SELECT * FROM question WHERE examId=".$examId;
  $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
  $i=1;
  $markObtained=0;
  $markPenality=0;
  while($questionRow=mysqli_fetch_assoc($questionResult)){
    $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
    $optionResult=mysqli_query($con,$optionQuery) or die('Error to send query');
    $answerQuery="SELECT answerOption AS ao,status AS ast FROM answer WHERE examId=".$examId."  AND candidateId='".$candidateId."' AND questionId=".$questionRow['questionId']." LIMIT 1";
    $answerResult=mysqli_query($con,$answerQuery) or die('Error to send querrry');
    $answerRow=mysqli_fetch_assoc($answerResult);
    $correctAQuery="SELECT answerOption AS ao FROM question WHERE questionId=".$questionRow['questionId']." LIMIT 1";
    $correctAResult=mysqli_query($con,$correctAQuery) or die('Error to send query');
    $correctARow=mysqli_fetch_assoc($correctAResult);
    if($answerRow['ast']==='wrong'){
      $color="red";
      $status="Incorrect";
      $markPenality++;
    }else{
      $color="#1DCD39";
      $status="Correct";
      $markObtained++;
    }
    echo "<p><strong>".$i."</strong> ".$questionRow['questionTitle']."    <i style='color:".$color.";'>".$status."</i></p>";
    $choice=65;
    $valArr=explode('n',$answerRow['ao']);
    $choosed=end($valArr);
    while($optionRow=mysqli_fetch_assoc($optionResult)){
      if(chr($choice)===chr(64+$choosed)){
        echo "<label><strong  style='color:".$color."'>".chr($choice)."</strong><input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."' checked>".$optionRow['optionTitle']."</label></br>";
      }else{
        echo "<label><strong>".chr($choice)."</strong><input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."' disabled>".$optionRow['optionTitle']."</label></br>";

      }
      $choice++;
    }
    if($answerRow['ast']==='wrong'){
      $valArr=explode('n',$correctARow['ao']);
      $correct=end($valArr);
      echo "<p style='green'>Answer=".chr(64+$correct)."</p>";
    }
    $i++;

  }
  $totalObtainedMark=($examRow['mra']*$markObtained)-($examRow['mwa']*$markPenality);
  $maximumMark=$examRow['tq']*$examRow['mra'];
  echo "<p>Total Mark ".$totalObtainedMark."/".$maximumMark."</p>";
  $markQuery="INSERT INTO mark (candidateId,examId,maximumMark,obtainedMark) VALUES('".$candidateId."',".$examId.",".$maximumMark.",".$totalObtainedMark.")";
  mysqli_query($con,$markQuery) or die('Error to send query');

  ?>
</body>
</html>
