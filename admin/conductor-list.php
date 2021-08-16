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



//Delete selected conductor from db
if(isset($_POST['delete'])){
  $conductorQuery="SELECT username FROM conductor";
  $conductorResult=mysqli_query($con,$conductorQuery) or die('Error to send query');
  while($conductorRow=mysqli_fetch_assoc($conductorResult)){
    $conductorId=$conductorRow['username'];
    if(isset($_POST[$conductorId])){
      if(!empty($_POST[$conductorId])){
        $deleteQuery="DELETE FROM conductor WHERE username='".$conductorId."'";
        mysqli_query($con,$deleteQuery) or die('Error to send query');
      }
    }

  }
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


  $checkQuery="SELECT COUNT(*) AS i FROM conductor WHERE username='".$uname."'";
  $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
  $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch result');
  if($checkRow['i']==='0'){
    $query="INSERT INTO conductor VALUES('".$uname."','".$hashed."','".$prefix."','".$fname."','".$lname."','".$role."','".$email."')";
    if(mysqli_query($con,$query)){
    }else{
      die("Failed to add");
    }
  }else{
  die("Username already taken");
  }
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>ONEC</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Plugins css -->
<link rel="stylesheet" href="../assets/plugins/charts-c3/c3.min.css"/>

<!-- Core css -->
<link rel="stylesheet" href="../assets/css/main.css"/>
<link rel="stylesheet" href="../assets/css/theme1.css"/>
</head>

<body class="font-montserrat">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
    </div>
</div>

<div id="main_content">
    <div id="left-sidebar" class="sidebar ">
        <h5 class="brand-name">ONEC</h5>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul class="metismenu">
                <li class="g_heading">Navigation</li>
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                <li><a href="exam-list.php"><i class="fa fa-list-ul"></i><span>Examinations</span></a></li>
                <li><a href="candidate-list.php"><i class="fa fa-list-ul"></i><span>Candidates</span></a></li>
                <li class="active"><a href="conductor-list.php"><i class="fa fa-list-ul"></i><span>Conductors</span></a></li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow arrow-c"><i class="fa fa-lock"></i><span>Authentication</span></a>
                    <ul>
                        <li><a href="change-password.php">Change Password</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>

    <div class="page">
        <div id="page_top" class="section-body top_dark">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <a href="javascript:void(0)" class="icon menu_toggle mr-3"><i class="fa  fa-align-left"></i></a>
                        <h1 class="page-title">Conductors</h1>
                    </div>
                    <div class="right">

                        <div class="notification d-flex">
                            <div class="dropdown d-flex">
                                <a class="nav-link icon d-none d-md-flex btn btn-default btn-icon ml-2" data-toggle="dropdown"><i class="fa fa-user"></i></a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                    <a class="dropdown-item" href="logout.php"><i class="dropdown-icon fa fa-sign-out"></i> Sign out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="section-body">
      <div class="container-fluid">
               <div class="d-flex justify-content-between align-items-center">
                   <ul class="nav nav-tabs page-header-tab">
                       <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#todo-list">Conductor List</a></li>
                       <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#todo-add">Add Conductor</a></li>
                   </ul>
               </div>
           </div>
        </div>

            <div class="section-body mt-3">
            <div class="container-fluid">

                  <div class="tab-content">
                  <div class="tab-pane fade show active" id="todo-list" role="tabpanel">
                  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                  <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <input type='submit' class="btn btn-primary btn-block"  name='search' value='Search'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">

                                    <thead>
                                        <tr>
                                           <th></th>
                                           <th>Full Name</th>
                                           <th>User Name</th>
                                           <th>Role</th>
                                           <th>Email</th>
                                           <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $query="SELECT username,prefix,firstName,lastName,role ,email FROM conductor ORDER BY firstName,lastName";
                                      $result=mysqli_query($con,$query) or die('Error to send query');
                                      if(mysqli_num_rows($result)){
                                        while($row=mysqli_fetch_assoc($result)){
                                         $conductorId=$row['username'];
                                         echo "<tr><td><span><input type='checkbox' name='".$conductorId."'></span></td>
                                              <td><a href='conductor-detail.php?conductorId=".$conductorId."'>".$row['prefix']." ".$row['firstName']." ".$row['lastName']."</a></td>
                                              <td><span>".$conductorId."</span></td>
                                              <td><span>".$row['role']."</span></td>
                                              <td><span>".$row['email']."</span></td>
                                              <td><a href='delete-conductor.php?conductorId=".$conductorId."'>Delete</td></tr>";
                                        }
                                      }else{
                                        echo "<tr><td colspan='5' style='text-align:center;'>No conductor found.</td></tr>";
                                      }
                                      ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <input type='submit' class="btn btn-primary btn-block" name='delete' value='Delete'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="todo-add" role="tabpanel">
                      <div class="card">
                          <form  action="<?=$_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data" class="card-body">
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">User Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='uname'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Prefix<span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <select name='prefix' class="form-control show-tick">
                                          <option >Select</option>
                                          <option value='Mr.'>Mr.</option>
                                          <option value='Mrs.'>Mrs.</option>
                                          <option value='Ms.'>Dr.</option>
                                          <option value='Prof.'>Prof.</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='text' name='fname' class="form-control">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='lname'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Role<span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type="text" class="form-control" name='role'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='email' class="form-control"  name='email' placeholder='example@example.com'>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                                  <div class="col-md-7">
                                      <input type='password' class="form-control"  name='pass'>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label"></label>
                                  <div class="col-md-7">
                                      <button type="submit" name='add' class="btn btn-primary">Add</button>
                                      <button type="submit" class="btn btn-outline-secondary">Cancel</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

                </div>

              </div>
            </div>



        </div>
    </div>
</div>


<script src="../assets/bundles/lib.vendor.bundle.js"></script>

<script src="../assets/bundles/apexcharts.bundle.js"></script>
<script src="../assets/bundles/counterup.bundle.js"></script>
<script src="../assets/bundles/knobjs.bundle.js"></script>
<script src="../assets/bundles/c3.bundle.js"></script>
<script src="../assets/js/core.js"></script>
<script src="../assets/js/page/project-index.js"></script>
</body>
</html>
