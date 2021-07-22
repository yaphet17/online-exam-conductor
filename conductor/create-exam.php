<?php
session_start();
require('../config.php');

$conductorId='@Nemeraa';//$_SESSION['conductorId'];

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}

$query="SELECT username,password FROM administrator WHERE username='".$_SESSION['uname']."' LIMIT 1";
$result=mysqli_query($con,$query) or die("Can't execute query");
if($row=mysqli_fetch_assoc($result)){
  if(!password_verify($_SESSION['password'],$row['password'])){
      session_destroy();
    die("Not authorized");
  }
}else{
  die('fatech failed');
}

//Submitting user input
if(isset($_POST['create'])){
  $examTitle=$_POST['examTitle'];
  $examDT=$_POST['examDateTime'];
  $examDuration=$_POST['examDuration'];
  $totalQuestion=$_POST['totalQuestion'];
  $markPRA=$_POST['markPRA'];
  $markPWA=$_POST['markPWA'];

  //Sanitize user input
  $examTitle=stripcslashes($examTitle);

  //Current date and time
  $examCD=date('y-m-d h:i:s');

  //Generate exam code
  $examCode=bin2hex(openssl_random_pseudo_bytes(7));

  //Convert datetime format
  $examDateTime=explode('T',$_POST['examDateTime']);
  $examDateTime=$examDateTime[0]." ".$examDateTime[1];

  $columns="conductorId,examTitle,examCreationDate,examDateTime,examDuration,totalQuestion,marksPerRightAnswer,marksPerWrongAnswer,examCode,examStatus";

   $query="INSERT INTO examination (".$columns.") VALUES('".$conductorId."','".$examTitle."','".$examCD."','".$examDT."','".$examDuration."',".$totalQuestion.",".$markPRA.",".$markPWA.",'".$examCode."','created')";
   if(mysqli_query($con,$query)){
     echo "<p>Succesfully created</p>";
   }else{
     echo "<p>Failed to create the exam</p>";
   }

}
?>
<!doctype html>
<html>
<head>
</head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
    <textarea type='textarea' name='examTitle' placeholder='Exam Title'></textarea>
    <input type='datetime-local' name='examDateTime' step='1' placeholder="Exam date and time">
    <input type='text' name='examDuration' placeholder="Duration(HH:MM:SS)">
    <input type='number' name='totalQuestion' placeholder='Total Question' min='1' value='1'>
    <input type='number' name='markPRA' placeholder='Marks per right answer' min='0'  value='1'>
    <input type='number' name='markPWA' placeholder='Marks per wrong answer' max='0' value='0'>
    <input type='submit' name='create' value='Create'>
  </form>
  <p>Exams</p>
  <table>
    <th>Title</th>
    <th colspan="2"></th>
  <?php
    $idQuery="SELECT * FROM examination";
    $idResult=mysqli_query($con,$idQuery) or die('Error to send query');
    while($idRow=mysqli_fetch_assoc($idResult)){
      echo "<tr><td>".$idRow['examTitle']."</td><td><a href='add-question.php?examId=".$idRow['examId']."'>Add question</a> <a href='add-to-exam.php?examId=".$idRow['examId']."'>Add candidate</a></td></tr>";
    }
  ?>
</body>
</html>
