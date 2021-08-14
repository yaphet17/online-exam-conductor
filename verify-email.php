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


$uname=$_SESSION['uname'];

if(isset($_POST['verify'])){
  $verifyQuery="SELECT verificationCode AS vc,verificationStatus AS vs FROM candidate WHERE candidateId='".$uname."' LIMIT 1";
  $verifyResult=mysqli_query($con,$verifyQuery) or die('Error to send query');
  $verifyRow=mysqli_fetch_assoc($verifyResult) or die('Error to fetch queryyy');
  if($verifyRow['vc']===$_POST['vcode']){
      $statusQuery="UPDATE candidate SET verificationStatus='verified' WHERE candidateId='".$uname."'";
      mysqli_query($con,$statusQuery) or die('Error to send query');
  }else{
    die('Invalid verification code');
  }
}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
  <?php
  if(isset($_GET['verificationCode'])){
    $verifyQuery="SELECT verificationCode AS vc,verificationStatus AS vs FROM candidate WHERE candidateId='".$uname."' LIMIT 1";
    $verifyResult=mysqli_query($con,$verifyQuery) or die('Error to send query');
    $verifyRow=mysqli_fetch_assoc($verifyResult) or die('Error to fetch query');
    if($verifyRow['vs']==='verified'){
      die('Your account is already verified');
    }else{
      if($verifyRow['vc']===htmlspecialchars($_GET['verificationCode'])){
        $statusQuery="UPDATE candidate SET verificationStatus='verified' WHERE candidateId='".$uname."'";
        mysqli_query($con,$statusQuery) or die('Error to send query');
        header('Location: dashboard.php');
      }else{
        die('Invalid verification code');
      }
    }
  }else{
    echo "<input type='text' name='vcode' placeholder='Verification Code'></br><input type='submit' name='verify' value='Verify'>";
  }
  ?>
</form>
</body>
</html>
