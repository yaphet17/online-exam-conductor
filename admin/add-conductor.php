<?php
session_start();
require('../config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='b893e95'){
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
  die('fetch failed');
}

//Submitting main form
if(isset($_POST['add'])){
  $uname=$_POST['uname'];
  $prefix=$_POST['prefix'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];
  $role=$_POST['role'];
  $email=$_POST['email'];
  $pass=$_POST['pass'];

  //Sanitizing user input
  $uname=stripcslashes($uname);
  $fname=stripcslashes($fname);
  $lname=stripcslashes($lname);
  $role=stripcslashes($role);
  $email=stripcslashes($email);
  $pass=stripcslashes($pass);

  //Encrypt password
  $hashed=password_hash($pass,PASSWORD_DEFAULT);


  //Generate verification bind_textdomain_codeset
  $verificationCode=bin2hex(openssl_random_pseudo_bytes(7));

  $checkQuery="SELECT COUNT(*) AS i FROM conductor WHERE username='".$uname."'";
  $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
  $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch result');
  if($checkRow['i']==='0'){
    $query="INSERT INTO conductor VALUES('".$uname."','".$hashed."','".$prefix."','".$fname."','".$lname."','".$role."','".$email."','".$verificationCode."','unverified')";
    if(mysqli_query($con,$query)){
      echo "<p>Successfully added</p>";
    }else{
      echo "Failed to add";
    }
  }else{
    echo "<p>Username already taken</p>";
  }

}


?>
<!doctype html>
<html>
<head></head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
  <input type='text' name='uname' placeholder="username">
  <select name='prefix'>
    <option disabled hidden selected>Prefix</option>
    <option value='Mr.'>Mr.</option>
    <option value='Mrs.'>Mrs.</option>
    <option value='Ms.'>Dr.</option>
    <option value='Prof.'>Prof.</option>
  </select>
  <input type='text' name='fname' placeholder="First Name">
  <input type='text' name='lname' placeholder="Last Name">
  <input type='text' name='role' placeholder="Role">
  <input type='email' name='email' placeholder="Email">
  <input type='password' name='pass' placeholder="Password">
  <input type='submit' name='add' value='Add'>
</form>
</body>
</html>
