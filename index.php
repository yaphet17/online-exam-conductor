<?php
session_start();
require('config.php');
?>

<!DOCTYPE html>

<html>

<head lang="en">
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/index.css" >
</head>


<body>
    <header>
        <div class='header'>
            <p class='title'>ONEC</p>
        </div>
    </header>
    <div class="main_container">
        <div class="signForm">
            <form action="<?=$_SERVER['PHP_SELF']?>" method='POST' >
                    <span>
                        <p class='signLabel'>Signin</p>
                    </span>
                    <span>
                      <?php
                      if(isset($_POST['signin'])){
                        //Remember login credentials
                        if(!empty($_POST["remember"])) {
                           setcookie ("uname",$_POST["uname"],time()+ 3600);
                           setcookie ("pass",$_POST["pass"],time()+ 3600);
                          } else {
                             setcookie("username","");
                             setcookie("password","");
                          }

                        $uName=$_POST['uname'];
                        $pass=$_POST['pass'];

                        //Sanitize user input
                        $uName=stripcslashes($uName);
                        $pass=stripcslashes($pass);

                        $query="SELECT candidateId,password,email,verificationStatus FROM candidate WHERE candidateId='".$uName."' LIMIT 1";
                        $result=mysqli_query($con,$query) or die("Error to send query");
                        $numRows=mysqli_num_rows($result);
                        if($numRows!=0){
                          $row=mysqli_fetch_assoc($result) or die("Error to fetch query");
                          if(password_verify($pass,$row['password'])){
                              $_SESSION['uname']=$uName;
                              $_SESSION['pass']=$pass;
                              $_SESSION['level']="a8226c2";
                              if($row['verificationStatus']==='unverified'){
                                header('Location: verify-email.php');
                              }else{
                                header('Location: dashboard.php');
                              }
                          }else{
                          echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:20px;color:red;margin-right:5px;'></i>Invalid Password</p>";
                          }
                        }else{
                          echo "<p style='color:red;'><i class='fa fa-warning' style='font-size:20px;color:red;margin-right:5px;'></i>Invalid Username</p>";
                        }

                      } ?>
                    </span>
                    <input type="text" class="email signField" name='uname' placeholder="Username" value="<?php if(isset($_COOKIE["uname"])) { echo $_COOKIE["uname"]; } ?>" required>
                    <input type="password" id="password" name='pass' value="<?php if(isset($_COOKIE["pass"])) { echo $_COOKIE["pass"]; } ?>" class="password signField" placeholder="Password" required>
                    <span><input type="submit" id="signin" value="Signin" name='signin' class="submitSign"></span>
                    <span class='remeber-box' ><label><input type="checkbox" name='remember' class='remeber' <?php if(isset($_COOKIE['uname'])){echo "checked";}?>> Remeber Me<label></span>
            </form>
        </div>
    </div>
</body>

</html>
