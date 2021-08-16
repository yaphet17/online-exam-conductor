<?php
session_start();
require_once'../vendor/autoload.php';
require('../config.php');

$conductorId=$_SESSION['conductorId'];
$pass=$_SESSION['pass'];
if(!isset($conductorId) or !isset($pass) or !isset($_SESSION['level']) or empty($conductorId) or empty($pass) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}

if(isset($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{
  die("exam id not found");
}

  $examQuery="SELECT examTitle AS et,examInstruction as ein FROM examination WHERE examId=".$examId." LIMIT 1";
  $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
  $examRow=mysqli_fetch_assoc($examResult);
  $examTitle=$examRow['et'];
  $html="<div style='display:flex;justify-content:center;'><p style='text-align:center;background-color:#0A0638;text-align:center;font-size:28px;color:#F1F1F1;'>".$examRow['et']."</p></div>";
  $html.="<div><p>".$examRow['ein']."</div>";
  $questionQuery="SELECT * FROM question WHERE examId=".$examId;
  $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
  $i=1;
  while($questionRow=mysqli_fetch_assoc($questionResult)){
    $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
    $optionResult=mysqli_query($con,$optionQuery) or die('Error to send querry');
    $html.="<p><strong>".$i.". </strong> ".$questionRow['questionTitle']."</p>";
    $choice=65;
  while($optionRow=mysqli_fetch_assoc($optionResult)){
    $html.="<label style='padding-left:20px;'>
              <strong>".chr($choice++).". </strong>
              ".$optionRow['optionTitle']."
            </label><br>";
  }
  $i++;
}

//Generating pdf file
$mpdf = new \Mpdf\Mpdf(['mode'=>'utf-8','format'=>'A4-L']);
$mpdf->WriteHTML($html);
$mpdf->SetWatermarkText('Examination');
$mpdf->showWatermarkText=true;
$mpdf->watermarkTextAlpha=0.1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->Output($examTitle.".pdf", 'D');
exit;

?>
