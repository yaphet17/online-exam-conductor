<?php
session_start();
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

if(isset($_GET['examId']) AND !empty($_GET['examId'])){
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
        <link type="text/css" rel="stylesheet" href="../assets/css/index.css"/>
        <link type="text/css" rel="stylesheet" href="../assets/css/exam.css"/>
        <style>
          .question{
            padding-bottom: 20px;
          }
          .controller{
            justify-content: flex-start;
            margin-left: 10px;
          }
          .controller a{
            display:inline-block;
            margin-right:10px;
            width:100px;
            height:30px;
            background-color:#0A0638;
            color:#fff;
            text-decoration: none;
            text-align:center;
            padding:5px;
            border-radius:5px;
          }
          .controller a:hover{
            background-color: rgba(10,6,56,0.9);
          }

        </style>
    </head>
    <body>
        <header>
            <div class="header">
            <div class='title'><p>Exam Preview</p></div>
            </div>
          </header>
          <div class="main-container">

              <div class='question'>
                <?php
                echo "<div class='exam-instruction'><p>Choose the best answer</p></div>";
                $questionQuery="SELECT * FROM question WHERE examId=".$examId;
                $questionResult=mysqli_query($con,$questionQuery) or die('Error to send query');
                $i=1;
                while($questionRow=mysqli_fetch_assoc($questionResult)){
                  $optionQuery="SELECT * FROM option WHERE questionId=".$questionRow['questionId']." ORDER BY optionNumber";
                  $optionResult=mysqli_query($con,$optionQuery) or die('Error to send querry');
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
            <a  href="export-exam.php?examId=<?php echo $examId;?>">Export PDF</a>
            <a href='dashboard.php'>Back to home</a>
          </div>
       </div>
        </body>
</html>
