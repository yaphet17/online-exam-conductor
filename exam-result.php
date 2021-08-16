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

$candidateId=$_SESSION['uname'];
if(isset($_GET['examId']) and !empty($_GET['examId'])){
  $examId=htmlentities($_GET['examId']);
}else{
  die('exam id not found');
}

?>
<!DOCTYPE HTML>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="assets/css/index.css"/>
        <link type="text/css" rel="stylesheet" href="assets/css/user.css"/>
        <link type="text/css" rel="stylesheet" href="assets/css/exam.css"/>

    </head>
    <body>
        <header>
          <div class="header">
            <p class="title">ONEC</p>
            <div class="nav">
              <a href="dashboard.php" title="Home" onclick="disp(event,'main-container')" ><img src="assets/icons/home.png" width="25" height="30"/></a>
              <p  id="dropbtn"class='dropbtn hidden-in-lw'>MORE <i style='margin-left:5px;' class='fa fa-caret-down'></i></p>
              <div class='dropdown'>
                <div id="dropdown-content" class='dropdown-content'>
                  <a href="logout.php" title="Logout"><img src="assets/icons/logout2.png" width="25" height="27" /><p>Logout</p></a>
                </div>
                </div>
              <a href="logout.php" class="hidden-in-sw" title="Logout"><img src="assets/icons/logout.png" width="25" height="27" /></a>
            </div>
        </div>
          </header>
          <div class="main-container">
              <div class='question'>
                <?php
                $examQuery="SELECT examTitle AS et,totalQuestion AS tq,marksPerRightAnswer AS mra,marksPerWrongAnswer AS mwa FROM examination WHERE examId=".$examId." LIMIT 1";
                $examResult=mysqli_query($con,$examQuery) or die('Error to send query');
                $examRow=mysqli_fetch_assoc($examResult);
                //echo "<div class='exam-title'><p>".$examRow['et']."</p></div>";
                echo "<div class='exam-instruction'><p>Choose the best answer</p></div>";
                $questionQuery="SELECT * FROM question WHERE examId=".$examId;
                $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
                $i=1;
                $markObtained=0;
                $markPenality=0;
                while($questionRow=mysqli_fetch_assoc($questionResult)){
                  $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
                  $optionResult=mysqli_query($con,$optionQuery) or die('Error to send query');
                  $answerQuery="SELECT answerOption AS ao,status AS ast FROM answer WHERE examId=".$examId."  AND candidateId='".$candidateId."' AND questionId=".$questionRow['questionId']." LIMIT 1";
                  $answerResult=mysqli_query($con,$answerQuery) or die('Error to send query');
                  $answerRow=mysqli_fetch_assoc($answerResult) or die('Error to fetch query');
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
                  echo "<p class='question-title'><strong>".$i."</strong> ".$questionRow['questionTitle']."    <i style='color:".$color.";'>".$status."</i></p>";
                  $choice=65;
                  $valArr=explode('n',$answerRow['ao']);
                  $choosed=end($valArr);
                  while($optionRow=mysqli_fetch_assoc($optionResult)){
                    if(chr($choice)===chr(64+$choosed)){
                      echo "<label class='question-option'><strong  style='color:".$color."'>".chr($choice)."</strong><input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."' checked>".$optionRow['optionTitle']."</label></br>";
                    }else{
                      echo "<label class='question-option'><strong>".chr($choice)."</strong>
                            <input type='radio' name='question".$i."' value='".$questionRow['questionId']."-option".$optionRow['optionNumber']."' disabled>".$optionRow['optionTitle']."
                            </label></br>";

                    }
                    $choice++;
                  }
                  if($answerRow['ast']==='wrong'){
                    $valArr=explode('n',$correctARow['ao']);
                    $correct=end($valArr);
                    echo "<p class='answer'style='color:#1DCD39;'>Answer= ".chr(64+$correct)."</p>";
                  }
                  $i++;

                }
                $checkQuery="SELECT markid FROM mark WHERE candidateId='".$candidateId."' AND examId=".$examId;
                $checkResult=mysqli_query($con,$checkQuery);
                $totalObtainedMark=($examRow['mra']*$markObtained)-($examRow['mwa']*$markPenality);
                $maximumMark=$examRow['tq']*$examRow['mra'];
                if(mysqli_num_rows($checkResult)===0){
                  $markQuery="INSERT INTO mark (candidateId,examId,maximumMark,obtainedMark) VALUES('".$candidateId."',".$examId.",".$maximumMark.",".$totalObtainedMark.")";
                  mysqli_query($con,$markQuery) or die('Error to send query');
                }

                ?>
          </div>
          <div class='mark footer' style='padding-left:30px; background-color:#F1F1F1;'>
            <?php
                echo "<p style='font-size:1.5em;color:#0A0638;'>Total Mark ".$totalObtainedMark."/".$maximumMark."</p>";
            ?>
          </div>
       </div>
          <script>
              var dropbtn=document.getElementById("dropbtn");
              var dropdown = document.getElementsByClassName("scbtn");
              var i;
             for (i = 0; i < dropdown.length; i++) {
                dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
              function showDrop(){
                var dropdown=document.getElementById("dropdown-content");
                if(dropdown.style.display==="flex"){
                    dropdown.style.display='none';
                    dropbtn.childNodes[1].className="fa fa-caret-down";

                }
                else{
                    dropdown.style.display='flex';
                    dropdown.style.flexDirection='column';
                    dropbtn.childNodes[1].className="fa fa-caret-up";
                }
            }


            dropbtn.addEventListener('click',showDrop,false);
          </script>

        </body>
</html>
