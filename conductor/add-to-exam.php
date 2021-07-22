<?php
session_start();
require('../config.php');

if(!isset($_SESSION['uname']) or !isset($_SESSION['pass']) or !isset($_SESSION['level']) or empty($_SESSION['uname']) or empty($_SESSION['pass']) or empty($_SESSION['level'])){
      session_destroy();
      header('Location: index.php');
}else{
  if($_SESSION['level']!='26c2b89'){
    session_destroy();
    header('Location: index.php');
  }
}

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

//Add all student with in a section to exam
if(isset($_POST['addSection'])){
  $getSQuery="SELECT sectionId AS si FROM section";
  $getSResult=mysqli_query($con,$getSQuery) or die('Error to send query');
  while($sRow=mysqli_fetch_assoc($getSResult)) {
    if(isset($_POST[$sRow['si']])){
      $candidateQuery="SELECT candidateId AS ci FROM candidate WHERE sectionId=".$sRow['si'];
      $candidateResult=mysqli_query($con,$candidateQuery) or die('Error to send query');
      while($candidateRow=mysqli_fetch_assoc($candidateResult)){
        $checkQuery="SELECT COUNT(*) AS n FROM examenrollment WHERE candidateId='".$candidateRow['ci']."' AND examId='".$examId."'";
        $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
        $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch query');
        if($checkRow['n']==0){
          $query="INSERT INTO examenrollment (candidateId,examId,attendanceStatus) VALUES('".$candidateRow['ci']."',".$examId.",'notattended')";
          mysqli_query($con,$query) or die('Error to send query');
        }else{
          die('Already enrolled');
        }
      }
    }
  }
}

//Add all selected candidates to exam
if(isset($_POST['addCandidate'])){
  $getQuery="SELECT candidateId AS ci FROM candidate";
  $getResult=mysqli_query($con,$getQuery);
  while($getRow=mysqli_fetch_assoc($getResult)){
    if(isset($_POST[$getRow['ci']])){
      $checkQuery="SELECT COUNT(*) AS n FROM examenrollment WHERE candidateId='".$getRow['ci']."' AND examId='".$examId."'";
      $checkResult=mysqli_query($con,$checkQuery) or die('Error to send query');
      $checkRow=mysqli_fetch_assoc($checkResult) or die('Error to fetch query');
      if($checkRow['n']==0){
        $insertQuery="INSERT INTO examenrollment (candidateId,examId,attendanceStatus) VALUES('".$getRow['ci']."',".$examId.",'notattended')";
        mysqli_query($con,$insertQuery) or die('Error to send query');
      }else{
        die('Already enrolled');
      }

    }
  }
}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
  <p>Add by individual section</p>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
    <lable>Section<select name="sectionFilter1">
                    <option value='all' selected>All</option>
                    <?php
                      //Get maximum section number from db
                      $checkSecQuery="SELECT MAX(sectionName) AS maxS FROM section LIMIT 1";
                      $checkSecResult=mysqli_query($con,$checkSecQuery) or die('Error to send query');
                      $checkSecRow=mysqli_fetch_assoc($checkSecResult);
                      $maxSL=explode(' ',$checkSecRow['maxS']);
                      $maxS=end($maxSL);
                      for($i=1;$i<=$maxS;$i++){
                        echo "<option value='section ".$i."'>".$i."</option>";
                      }
                      echo "</select></label><label>Academic Year<select name='academicYear1'>
                        <option value='all'>All</option>
                        <option value='1'>1st Year</option>
                        <option value='2'>2nd Year</option>
                        <option value='3'>3rd Year</option>
                        <option value='4'>4th Year</option>
                        <option value='5'>5th Year</option>
                      </select></label>";
                      //Get all department from db
                      $dQuery="SELECT department AS d FROM section GROUP BY department";
                      $dResult=mysqli_query($con,$dQuery) or die('Error to send query');
                      echo "<lable> Department<select name='department1'><option value='all' selected>All</option>";
                      while($dRow=mysqli_fetch_assoc($dResult)){
                        echo "<option value='".$dRow['d']."'>".$dRow['d']."</option>";
                      }
                      echo "</select></label>";

                    ?>
                    <input type='submit' name='filter1' value='Filter'>
                  </form>
  <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
    <table>
      <th></th>
      <th>Section Name</th>
      <th>Academic Year</th>
      <th>Department</th>
    <?php
    $sectionQuery="SELECT * FROM section ORDER BY academicYear,sectionName,department";
    if(isset($_POST['filter1'])){
      $sectionFilter1=$_POST['sectionFilter1'];
      $academicYear1=$_POST['academicYear1'];
      $department1=$_POST['department1'];
      if($sectionFilter1!="all" and $academicYear1!="all" and   $department1!="all"){
        $sectionQuery="SELECT * FROM section WHERE sectionName='".$sectionFilter1."' AND academicYear=".$academicYear1." AND department='".$department1."' ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1!="all" and $academicYear1==="all" and $department1==="all"){
          $sectionQuery="SELECT * FROM section WHERE sectionName='".$sectionFilter1."' ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1==="all" and $academicYear1!="all" and $department1==="all"){
          $sectionQuery="SELECT * FROM section WHERE academicYear=".$academicYear1." ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1==="all" and $academicYear1==="all" and $department1!="all"){
          $sectionQuery="SELECT * FROM section WHERE department='".$department1."' ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1!="all" and $academicYear1!="all" and $department1==="all"){
          $sectionQuery="SELECT * FROM section WHERE sectionName='".$sectionFilter1."' AND academicYear=".$academicYear1." ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1!="all" and $academicYear1==="all" and $department1!="all"){
          $sectionQuery="SELECT * FROM section WHERE sectionName='".$sectionFilter1."' AND department='".$department1."' ORDER BY academicYear,sectionName,department";
      }else if($sectionFilter1==="all" and $academicYear1!="all" and $department1!="all"){
          $sectionQuery="SELECT * FROM section WHERE academicYear=".$academicYear1." AND department='".$department1."' ORDER BY academicYear,sectionName,department";
      }

    }
    $sectionResult=mysqli_query($con,$sectionQuery) or die('Error to send query');
    //Check if any candidate matches the filter
    if(mysqli_num_rows($sectionResult)===0){
      echo "<tr><td colspan='6' style='text-align:center;'>No match found</td></tr>";
    }else{
      $i=0;
      while($sectionRow=mysqli_fetch_assoc($sectionResult)){
        echo "<tr><td><input type='checkbox' id='".$i."' name='".$sectionRow['sectionId']."'></td><td><label for='".$i."'>".$sectionRow['sectionName']."</label></td><td><label for='".$i."'>".$sectionRow['academicYear']."</label></td><td><label for='".$i."'>".$sectionRow['department']."</label></td<tr>";
        $i++;
      }
    }
    ?>
  </table></br>
  <input type='submit' name='addSection' value='Add Section'>
  </form>
  <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo $_GET['examId'];}?>">
    <p>Add individual candidate</p>
      <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
        <lable>Section<select name="sectionFilter">
                        <option value='all' selected>All</option>
                        <?php
                          //Get maximum section number from db
                          $checkSecQuery="SELECT MAX(sectionName) AS maxS FROM section LIMIT 1";
                          $checkSecResult=mysqli_query($con,$checkSecQuery) or die('Error to send query');
                          $checkSecRow=mysqli_fetch_assoc($checkSecResult);
                          $maxSL=explode(' ',$checkSecRow['maxS']);
                          $maxS=end($maxSL);
                          for($i=1;$i<=$maxS;$i++){
                            echo "<option value='section ".$i."'>".$i."</option>";
                          }
                          echo "</select></label><label>Academic Year<select name='academicYear'>
                            <option value='all'>All</option>
                            <option value='1'>1st Year</option>
                            <option value='2'>2nd Year</option>
                            <option value='3'>3rd Year</option>
                            <option value='4'>4th Year</option>
                            <option value='5'>5th Year</option>
                          </select></label>";
                          //Get all department from db
                          $dQuery="SELECT department AS d FROM section GROUP BY department";
                          $dResult=mysqli_query($con,$dQuery) or die('Error to send query');
                          echo "<lable> Department<select name='department'><option value='all' selected>All</option>";
                          while($dRow=mysqli_fetch_assoc($dResult)){
                            echo "<option value='".$dRow['d']."'>".$dRow['d']."</option>";
                          }
                          echo "</select></label>";

                        ?>
                        <input type='submit' name='filter2' value='Filter'>
      </form>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <input type='hidden' name='examId' value="<?php if(!empty($_GET['examId'])){echo htmlspecialchars($_GET['examId']);}?>">
      <table>
        <th></th>
        <th>First Name</th>
        <th>Last name</th>
        <th>Sex</th>
        <th>Academic Year</th>
        <th>Department</th>
        <th>Section</th>
        <?php
          //Adding all candidate to the table
          $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId ORDER BY s.academicYear,s.sectionName,s.department,c.firstName,c.lastName";
          //Filter candidates
          if(isset($_POST['filter2'])){
            if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']==="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND  s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']!="all" and $_POST['department']==="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.academicYear=".$_POST['academicYear']." ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']!="all" and $_POST['academicYear']==="all" and $_POST['department']!="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.sectionName='".$_POST['sectionFilter']."' AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
            }else if($_POST['sectionFilter']==="all" and $_POST['academicYear']!="all" and $_POST['department']!="all"){
                $candListQuery="SELECT c.candidateId AS ci,c.firstName AS fname,c.lastName AS lname,c.sex AS sx,s.sectionName AS section,s.academicYear AS year,s.department AS depart FROM section s,candidate c WHERE c.sectionId=s.sectionId AND s.academicYear=".$_POST['academicYear']." AND s.department='".$_POST['department']."' ORDER BY s.academicYear,s.sectionName,s.department";
            }

          }
          $candListResult=mysqli_query($con,$candListQuery) or die('Error to send query');
          //Check if any candidate matches the filter
          if(mysqli_num_rows($candListResult)===0){
            echo "<tr><td colspan='6' style='text-align:center;'>No match found</td></tr>";
          }else{
            $j=-1;
            //List all candidate qualifies the filter
            while($candListRow=mysqli_fetch_assoc($candListResult)){
              echo "<tr>
                <td><input type='checkbox' id='".$j."' name='".$candListRow['ci']."'></td>
                <td><label for='".$j."'>".$candListRow['fname']."</label></td>
                <td><label for='".$j."'>".$candListRow['lname']."</label></td>
                <td><label for='".$j."'>".$candListRow['sx']."</label></td>
                <td><label for='".$j."'>".$candListRow['year']."</label></td>
                <td><label for='".$j."'>".$candListRow['depart']."</label></td>
                <td><label for='".$j."'>".$candListRow['section']."</label></td>
              </tr>";
              $j--;
            }
          }


        ?>
      </table>
      <input type="submit" name="addCandidate" value="Add Candidate">
    </form>
</body>
</html>
