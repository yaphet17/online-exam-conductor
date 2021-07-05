<?php
require_once('config.php');


/*if(!isset($_SESSION['uname'])){
  header('Location: admin-login.php');
}
$query="SELECT username,password FROM administrator WHERE username='".$_SESSION['uname']."' LIMIT 1";
$result=mysqli_query($con,$query) or die("Can't execute query");
if($row=mysqli_fetch_assoc($result)){
  if(!password_verify($_SESSION['password'],$row['password'])){
    die("Not authorized");
  }
}else{
  die('fatech failed');
}*/

//Submitting main form
if(isset($_POST['add'])){
  $id=$_POST['id'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];
  $sex=$_POST['sex'];
  $section=$_POST['section'];
  $email=$_POST['email'];
  $pass=$_POST['pass'];

  //Santizing user input
  $id=stripcslashes($id);
  $fname=stripcslashes($fname);
  $lname=stripcslashes($lname);
  $email=stripcslashes($email);
  $pass=stripcslashes($pass);
  $cImage=getPath();

  //Encrypt password
  $hashed=password_hash($pass,PASSWORD_DEFAULT);

  //Current date and posix_times
  $currDateTime=date('y-m-d h:i:s');

  //Generat verification bind_textdomain_codeset
  $verificationCode=bin2hex(openssl_random_pseudo_bytes(7));

  //Check if there a candidate with the same id or Username
  $checkQuery="SELECT count(candidateId) AS i FROM candidate WHERE candidateId='".$id."'";
  $result=mysqli_query($con,$checkQuery) or die('Error to send query');
  $checkRow=mysqli_fetch_assoc($result) or die('Error to fetch query');
  if($checkRow['i']==='0'){
    //Inserting user input to db
    $query="INSERT INTO candidate VALUES('".$id."','".$hashed."','".$fname."','".$lname."','".$sex."','".$cImage."','".$currDateTime."',".$section.",'".$email."','".$verificationCode."','unverified')";
    if(mysqli_query($con,$query)){
      echo '<p>Succesfully added</p>';
      if(isset($_POST['mailunp'])){
        $to=$email;
        $subject="ONEC account username and password";
        $msg="Dear ".$fname." ".$lname." below is your username and password for your onec account\nUsername: ".$id."\nPassword: ".$pass;
        if(mail($to,$subject,$msg)){
            echo '<p>email successfully sent</p>';
        }
      }
    }else{
        echo '<p>Succesfully failed</p>';
    }
  }else{
    echo "<p>User name already taken</p>";
  }
}

//Get File Path
function getPath(){
  $fileName=$_FILES['cImage']['name'];
  $fileTmpName=$_FILES['cImage']['tmp_name'];
  $fileSize=$_FILES['cImage']['size'];
  $fileError=$_FILES['cImage']['error'];
  $fileType=$_FILES['cImage']['type'];

  $fileExt=explode('.', $fileName);
  $fileActualExt=strtolower(end($fileExt));
  $allowed=array('jpg','jpeg','png');
  if(in_array($fileActualExt, $allowed)){
      if ($fileError===0){
           if($fileSize<1000000){
                 $fileNameNew=uniqid('',true).'.'.$fileActualExt;
                 $pathFolder='candidate-image';
                 $fileDestination=$pathFolder.'/'.$fileNameNew;
                 move_uploaded_file($fileTmpName, $fileDestination);
                 return $fileDestination;

           }else{
                 echo '<p>File Size too large!</p>';
           }
     }else{
          echo "<p>Some error occured</p>";
    }

}else{
  echo '<p>file type not allowed</p>';
}
}

?>
<!doctype html>
<html>
<head lang='en'>
</head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data">
  <input type='text' name='id' placeholder="User Name">
  <input type='text' name='fname' placeholder="First Name">
  <input type='text' name='lname'  placeholder="Last Name">
  <label>Male<input type='radio' name='sex' value='m'></label>
  <label>Female<input type='radio' name='sex' value='f'></label>

<select name="section" >
  <option selected disabled hidden>Section</option>
  <?php
    $query="SELECT * FROM section";
    $result=mysqli_query($con,$query) or die('Error to send query');
    if(mysqli_num_rows($result)!=0){
      while($row=mysqli_fetch_assoc($result)){
        if($row['academicYear']==='1'){
          $str="st Year";
        }else if($row['academicYear']==='2'){
          $str="2nd Year";
        }else if($row['academicYear']==='3'){
          $str="rd Year";
        }else{
          $str="th Year";
        }
        echo "<option value='".$row['sectionId']."'>".$row['sectionName']."-".$row['academicYear'].$str." ".$row['department']."</option>";
      }
    }else{
      echo "<option  disabled>No section added yet.</option>";
    }
  ?>
</select>
<input type='email' name='email'  placeholder="Email">
<input type='password' name='pass'  placeholder="Password">
<input type='file' name='cImage' id='cImage'>
<input type='submit' name='add' value='Add'>
</form>


</body>
</html>
