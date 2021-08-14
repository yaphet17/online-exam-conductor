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

$query="SELECT * FROM examination e,conductor c WHERE  e.examId=".$examId." LIMIT 1";
$result=mysqli_query($con,$query) or die('Error to send query');
$row=mysqli_fetch_assoc($result) or die('Error to fetch query');
$examTitle=$row['examTitle'];
$prefix=$row['prefix'];
$fname=$row['firstName'];
$lname=$row['lastName'];
$cDate=$row['examCreationDate'];
$eDate=$row['examDateTime'];
$eDura=$row['examDuration'];
$totQ=$row['totalQuestion'];
$mpr=$row['marksPerRightAnswer'];
$mpw=$row['marksPerRightAnswer'];
$tm=$totQ*$mpr;
$code=$row['examCode'];



  $html="<span style='display:flex;justify-content:center;'><h2 style='background-color:#0A0638;text-align:center;font-size:28px;color:#F1F1F1;'>Candidates Result</h2></span>";
$html.="<table cellspacing='0' cellspadding='0' border='1' style='width:100%;border-color:#0A0638;'>
        <thead>
          <tr style='background-color:#0A0638; border-color:#0A0638;'>
            <th style='color:#F1F1F1;'>Name</th>
            <th style='color:#F1F1F1;'>Section</th>
            <th style='color:#F1F1F1;'>Department</th>
            <th style='color:#F1F1F1;'>Academic Year</th>
            <th style='color:#F1F1F1;'>Attendance Status</th>
            <th style='color:#F1F1F1;'>Result</th>
          </tr>
        </thead>
        <tbody>
      ";
      $query="SELECT * FROM candidate c,examenrollment e WHERE c.candidateId=e.candidateId AND e.examId=".$examId." ORDER BY c.firstName,c.lastName,c.registrationDate DESC";
      $result=mysqli_query($con,$query);
      if(mysqli_num_rows($result)!=0){
      while($row=mysqli_fetch_assoc($result)){
        //Fetch section data
        $sectionQuery="SELECT * FROM section WHERE sectionId='".$row['sectionId']."'";
        $sectionResult=mysqli_query($con,$sectionQuery) or die('Error to send query');
        $sectionRow=mysqli_fetch_assoc($sectionResult) or die('Error to fetch query');
        //Fetch candidates exam result
        $html.="<tr style='background-color:#F1F1F1;height:30px; border-color:#fff;'>
                <td style='text-align:left;padding-left:5px;'>".$row['firstName']." ".$row['lastName']."</td>
                <td  style='text-align:center;'>".$sectionRow['sectionName']."</td>
                <td  style='text-align:center;'>".$sectionRow['department']."</td>
                <td  style='text-align:center;'>".$sectionRow['academicYear']."</td>
                <td  style='text-align:center;'>".$row['attendanceStatus']."</td>";
          $markQuery="SELECT maximumMark AS mm,obtainedMark AS om FROM mark WHERE candidateId='".$row['candidateId']."' AND examId=".$examId;
          $markResult=mysqli_query($con,$markQuery) or die('Error to send querry');
          if(mysqli_num_rows($markResult)!=0){
            $markRow=mysqli_fetch_assoc($markResult) or die('Error to fetch query');
            $html.="<td  style='text-align:center;'>".$markRow['om']."/".$markRow['mm']."</td></tr>";
          }else{
            $html.="<td  style='text-align:center;'>0</td></tr>";
          }

      }
      $html.="</tbody></table>";
    }
    $html.="
      <table>
      <thead>
        <tr>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Conducted by:</strong> ".$prefix." ".$fname." ".$lname."</td>
          <td><strong>Created on:</strong> ".$cDate."</td>
        </tr>
        <tr>
          <td><strong>Started in:</strong> ".$eDate."</td>
          <td><strong>Duration:</strong> ".$eDura."</td>
        </tr>
        <tr>
          <td><strong>Marks per right answer:</strong> ".$mpr."</td>
          <td><strong>Total question:</strong> ".$totQ."</td>
        </tr>
        <tr>
          <td><strong>Marks per wrong answer:</strong> ".$mpw."</td>
        <td><strong>Total mark:</strong> ".$tm."</td>
        </tr>
      </tbody>
      </table>
         ";

         //Generating pdf file
        $mpdf = new \Mpdf\Mpdf(['mode'=>'utf-8','format'=>'A4-L']);
       	$mpdf->WriteHTML($html);
       	$mpdf->SetWatermarkText('Exam Report');
       	$mpdf->showWatermarkText=true;
       	$mpdf->watermarkTextAlpha=0.1;
       	$mpdf->SetDisplayMode('fullpage');
       	$mpdf->Output($examTitle.".pdf", 'D');
       	exit;




?>
