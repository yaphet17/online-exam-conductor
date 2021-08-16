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

//Token checker
if(!isset($_SESSION['token'])){
  $redirect="Location: enroll-to-exam.php?examId='".$examId."'";
  header($redirect);
}

$candidateId=$_SESSION['uname'];

//Copy url value
if(isset($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
  $_SESSION['examId']=$examId;
}else{ //Alternate option if a page is refreshed by submit button
  if(isset($_POST['examId'])){
    $examId=$_POST['examId'];
  }else{
    if(isset($_SESSION['examId'])){
      $examId=$_SESSION['examId'];
    }else{
      die('Exam id not found.');
    }
  }
}

//Submit candidate answer to db
if(isset($_POST['submit'])){
$preventQuery="SELECT COUNT(*) AS c FROM answer WHERE examId=".$examId." AND candidateId='".$candidateId."'";
$preventResult=mysqli_query($con,$preventQuery) or die('Error to send queryy');
if(mysqli_num_rows($preventResult)>0){
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
deleteToken($candidateId,$examId,$con);
header("Location: exam-result.php?examId=".$examId);
}else{
  $redirect="Location: exam-result.php?examId=".$examId;
  header($redirect);
}
}

if(isset($_POST['leave'])){
  $statusQuery="UPDATE examenrollment SET attendanceStatus='leaved' WHERE candidateId='".$candidateId."' AND examId=".$examId;
  mysqli_query($con,$statusQuery) or die('Error to send query');
  if(isset($_SESSION['examId'])){
    unset($_SESSION['examId']);
  }
  deleteToken($candidateId,$examId,$con);
  header('Location: dashboard.php');
}

//Delete token from db
function deleteToken($candidateId,$examId,$con){
  $tokenQuery="DELETE FROM examtoken WHERE candidateId='".$candidateId."' AND examId=".$examId;
  mysqli_query($con,$tokenQuery) or die('Error to send query');
  unset($_SESSION['token']);
}

?>


<!DOCTYPE HTML>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="assets/css/index.css"/>
        <link type="text/css" rel="stylesheet" href="assets/css/exam.css"/>
        <noscript><meta http-equiv="refresh" content='1;url=no-script.php?<?php echo $candidateId; ?>'></noscript>
    </head>
    <body>
        <header>
            <div class="header">
              <?php
              $examQuery="SELECT examTitle AS et,examInstruction as ein FROM examination WHERE examId=".$examId." LIMIT 1";
              $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
              $examRow=mysqli_fetch_assoc($examResult);
              echo "<div class='title'><p>".$examRow['et']."</p></div>";
              ?>
              <div class='timer-section'><p>Remaining Time:  </p><p class='nav timer' id='timer'></p></div>
            </div>
          </header>
          <div class="main-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
              <div class='question'>
                <input type='hidden' id='examId' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
                <?php
                echo "<div class='exam-instruction'><p>".$examRow['ein']."</p></div>";
                $questionQuery="SELECT * FROM question WHERE examId=".$examId;
                $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
                $i=1;
                while($questionRow=mysqli_fetch_assoc($questionResult)){
                  $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
                  $optionResult=mysqli_query($con,$optionQuery) or die('Error to send query');
                  echo "<p class='question-title'><strong>".$i.". </strong> ".$questionRow['questionTitle']."</p>";
                  $choice=65;
                while($optionRow=mysqli_fetch_assoc($optionResult)){
                  echo "<label class='question-option'><strong>".chr($choice++)."</strong>
                      <input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."'>
                      ".$optionRow['optionTitle']."</label></br>";
                }
                $i++;
              }
              ?>
          </div>
            <div class='controller'>
              <input type='submit' name='submit' value='Submit'>
              <input type='submit'  name='leave' value='Leave Exam'>

            </div>
         </form>
       </div>
       <script type='text/javascript'>

              function checkWindowSize(){
                  var pageW = document.documentElement.clientWidth;
                  var pageH = document.documentElement.clientHeight;
                  var screenW=window.screen.width;
                  var screenH=window.screen.height;
                  if(screenW-pageW>100 || screenH-pageH>120 ){
                     window.location='dispell-candidate.php';
                  }

              }
              function checkFocus(){
                window.location='dispell-candidate.php';
              }
              // Timer request
              setInterval(function(){
              var xmlhttp=new XMLHttpRequest();
              xmlhttp.open("GET","timer.php",false);
              xmlhttp.send(null);
              document.getElementById('timer').innerHTML=xmlhttp.responseText;
              },1000);


              // Online/offline detection
              var counter=0;
              setInterval(function(){

                 if(!window.navigator.onLine){
	                  counter+=1;
                  }else if(window.navigator.onLine && counter===2){
	                   counter=0;
                     window.location='exam.php';
                   }
                  if(counter===2){
                  errorPage="<div style='width:100vw;height:100vh;  margin-left:30%; display:flex; justify-content:center;align-items;flex-direction:column;'><span><p style='font-size:50px'>Connection lost.</p>  <p style='font-size:20px;'>There seems to be a problem with your internet connection </p></span></div>";
                    document.body.innerHTML='';
                  document.body.innerHTML=errorPage;
                  }
                },5000);


                function  displayWindowSize(){
                     var counter=0;
                     var countDown;
			               var pageW = document.documentElement.clientWidth;
		                 var pageH = document.documentElement.clientHeight;
			               var screenW=window.screen.width;
                     var screenH=window.screen.height;

                     if(screenW-pageW>100 || screenH-pageH>120 ){
                       countDown=setInterval(()=>{
                         pageW = document.documentElement.clientWidth;
                         pageH = document.documentElement.clientHeight;
                         if(screenW-pageW>100 || screenH-pageH>120){
                           counter+=1;
                         }else{
                           clearInterval(countDown);
                           counter=0;
                           return;
                         }
                       if(counter===5){
                         window.location='dispell-candidate.php';
                       }

                      },1000);
                     }
		            }
		           window.addEventListener("resize", displayWindowSize);
               //window.addEventListener("blur", checkFocus);
               window.addEventListener("load", checkWindowSize);
          </script>
        </body>
</html>
