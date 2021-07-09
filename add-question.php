<?php
session_start();
require_once('config.php');

//Copy url value
if(isset($_GET['examId'])){
  $examId=$_GET['examId'];
}else{
  //Alternate option if a page is refreshed by submit button
  if(isset($_POST['examId'])){
    $examId=$_POST['examId'];
  }else{
    die('exam id not found');
  }
}


if(isset($_POST['add'])){
  $tqQuery="SELECT totalQuestion FROM examination WHERE examId=".$examId;
  $tqResult=mysqli_query($con,$tqQuery) or die('Error to send query');
  if($tqRow=mysqli_fetch_assoc($tqResult)){
  for($j=1;$j<=$tqRow['totalQuestion'];$j++){
  $qt="questionTitle".$j;
  $op="answerOption".$j;
  $questionTitle=$_POST[$qt];
  $option=$_POST[$op];

  //Sanitizing user input
  $questionTitle=stripcslashes($questionTitle);
  $option=stripcslashes($option);

  $columns="examId,questionTitle,answerOption";

  //Inseting user input to db
  $query="INSERT INTO question (".$columns.") VALUES(".$examId.",'".$questionTitle."','".$option."')";
  if(mysqli_query($con,$query)){
    echo "<p>Questions succesfully added</p>";
    $query="SELECT questionId AS qi FROM question WHERE questionTitle='".$questionTitle."'";
    $result=mysqli_query($con,$query) or die('Error to send query');
    if($row=mysqli_fetch_assoc($result)){
      $columns="questionId,optionNumber,optionTitle";
      $ot="option1-".$j;
      $optionTitle=$_POST[$ot];
      $optionTitle=stripcslashes($optionTitle);
      $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",1,'".$optionTitle."')";
      if(mysqli_query($con,$query)){
        echo "<p>Succesfully added option 1</p>";
      }else{
          echo "<p>Failed to add  option 1</p>";
      }
      $ot="option2-".$j;
      $optionTitle=$_POST[$ot];
      $optionTitle=stripcslashes($optionTitle);
      $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",2,'".$optionTitle."')";
      if(mysqli_query($con,$query)){
        echo "<p>Succesfully added option 2</p>";
      }else{
          echo "<p>Failed to add  option 2</p>";
      }
      $ot="option3-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",3,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
            echo "<p>Succesfully added option 3</p>";
          }else{
            echo "<p>Failed to add  option 3</p>";
          }
      }
      $ot="option4-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",4,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
            echo "<p>Succesfully added option 4</p>";
          }else{
            echo "<p>Failed to add  option 4</p>";
          }
      }
      $ot="option5-".$j;
      if(!empty($_POST[$ot])){
          $optionTitle=$_POST[$ot];
          $optionTitle=stripcslashes($optionTitle);
          $query="INSERT INTO option (".$columns.") VALUES(".$row['qi'].",5,'".$optionTitle."')";
          if(mysqli_query($con,$query)){
            echo "<p>Succesfully added option 5</p>";
          }else{
            echo "<p>Failed to add  option 5</p>";
          }
      }
    }
  }else{
    echo "<p>Failed to add question</p>";
  }
}
}
}

?>
<!doctype html>
<html>
<head></head>
<body>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
  <?php
  //Get total question
  $tqQuery="SELECT totalQuestion FROM examination WHERE examId=".$examId;
  $tqResult=mysqli_query($con,$tqQuery) or die('Error to send query');
  if($tqRow=mysqli_fetch_assoc($tqResult)){
    for($i=1;$i<=$tqRow['totalQuestion'];$i++){
      echo"<textarea name='questionTitle".$i."' placeholder='Question title'></textarea>
      <input type='text' name='option1-".$i."' placeholder='option 1'>
      <input type='text' name='option2-".$i."' placeholder='option 2'>
      <input type='text' name='option3-".$i."' placeholder='option 3'>
      <input type='text' name='option4-".$i."' placeholder='option 4'>
      <input type='text' name='option5-".$i."' placeholder='option 5'>
      <select name='answerOption".$i."'>
        <option disabled selected hidden>Answer Option</option>
        <option value='option1'>Option 1</option>
        <option value='option2'>Option 2</option>
        <option value='option3'>Option 3</option>
        <option value='option4'>Option 4</option>
        <option value='option5'>Option 5</option>
      </select></br>";
    }
  }else{
    echo "<p>Failed to fetch result</p>";
  }


  ?>

  <input type='submit' name='add' value='Add'>
</form>
</body>
</html>
