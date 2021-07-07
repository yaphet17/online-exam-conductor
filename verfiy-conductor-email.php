<?php
session_start();
require_once('config.php');

$uname="@Nemeraa";//$_SESSION['username'];

if(isset($_POST['verify'])){
  $verifyQuery="SELECT verificationCode AS vc,verificationStatus AS vs FROM conductor WHERE username='".$uname."' LIMIT 1";
  $verifyResult=mysqli_query($con,$verifyQuery) or die('Error to send query');
  $verifyRow=mysqli_fetch_assoc($verifyResult) or die('Error to fetch query');
  if($verifyRow['vs']==='verified'){
    die('Your account is already verified');
  }else{
    if($verifyRow['vc']===$_POST['vcode']){
      $statusQuery="UPDATE conductor SET verificationStatus='verified' WHERE username='".$uname."'";
      mysqli_query($con,$statusQuery) or die('Error to send query');
      echo "Succesfully verified";
    }else{
      die('Invalid verification code');
    }
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
    $verifyQuery="SELECT verificationCode AS vc,verificationStatus AS vs FROM conductor WHERE username='".$uname."' LIMIT 1";
    $verifyResult=mysqli_query($con,$verifyQuery) or die('Error to send query');
    $verifyRow=mysqli_fetch_assoc($verifyResult) or die('Error to fetch query');
    if($verifyRow['vs']==='verified'){
      die('Your account is already verified');
    }else{
      if($verifyRow['vc']===htmlspecialchars($_GET['verificationCode'])){
        $statusQuery="UPDATE conductor SET verificationStatus='verified' WHERE username='".$uname."'";
        mysqli_query($con,$statusQuery) or die('Error to send query');
        echo "Succesfully verified";
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
