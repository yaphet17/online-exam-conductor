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

?>
<!DOCTYPE HTML>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="assets/css/index.css"/>
        <link type="text/css" rel="stylesheet" href="assets/css/user.css"/>
        <link type="text/css" rel="stylesheet" href="assets/css/adminstrator.css"/>
    </head>
    <body>
        <header>
            <div class="header">
              <p class="title">ONEC</p>
              <div class="nav">
                <a href="#" title="Home" onclick="disp(event,'main-container')" ><img src="assets/icons/home.png" width="25" height="30"/></a>
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
            <div class="tab">
                <button class="tablinks" id="userTab" onclick="openTab(event,'userAdmin')" >Exam List</button>
                <button class="tablinks" onclick="openTab(event,'course')"> Exam Results</button>
            </div>
            <div id="userAdmin" class="tabcontent">
              <form action="<?=$_SERVER['PHP_SELF']?>" method='POST'>
                <div class="searchbar">
                    <span>
                        <input type="text" name='examTitle' placeholder="&#128269; Search by Title">
                        <button type='submit' name='search'>Search</button>
                    </span>
                </div>
              </form>
                <div class="userdata">
                        <table id="userTable">
                          <tr>
                            <th>Title</th>
                            <th>Exam Date and Time</th>
                            <th>Exam Duration</th>
                            <th>Total Question</th>
                            <th>Conductor</th>
                            <th>Exam Status</th>
                            <th>Enrollment</th>
                          </tr>
                          <?php
                          if(isset($_POST['search'])){
                              $examTitle=$_POST['examTitle'];
                              $query="SELECT e.examId AS ei,e.examTitle AS et,e.examDateTime AS edt,e.examDuration AS ed,e.totalQuestion AS tq,c.prefix AS p,c.firstName AS f,c.lastName AS l,e.examStatus AS es FROM examination e,conductor c WHERE e.examTitle='".$examTitle."' AND e.conductorId=c.username ORDER BY examDateTime DESC,examStatus DESC";
                          }else{
                            $query="SELECT e.examId AS ei,e.examTitle AS et,e.examDateTime AS edt,e.examDuration AS ed,e.totalQuestion AS tq,c.prefix AS p,c.firstName AS f,c.lastName AS l,e.examStatus AS es FROM examination e,conductor c WHERE e.conductorId=c.username ORDER BY examDateTime DESC,examStatus DESC";
                          }
                          $result=mysqli_query($con,$query) or die('Error to send query');
                          if(mysqli_num_rows($result)>0){
                            while($row=mysqli_fetch_assoc($result)){
                              if($row['es']!='started'){
                                $str='#';
                              }else{
                                $str="enroll-to-exam.php?examId=".$row['ei'];
                              }
                              echo "<tr><td>".$row['et']."</td><td>".$row['edt']."</td><td>".$row['ed']."</td><td>".$row['tq']."</td><td>".$row['p']." ".$row['f']." ".$row['l']."</td><td>".$row['es']."</td><td><a href='".$str."'>Enroll</a></td></tr>";
                            }

                          }else{
                            echo "<tr><td colspan='7' style='text-align:center;'>No match found.</td></tr>";
                          }
                          ?>
                </table>
                </div>
            </div>
            <div id="course" class="tabcontent">
                <table id="courseTable">
                    <tr>
                      <th>Exam Title</th>
                      <th>Exam Date</th>
                      <th>Total Question</th>
                      <th>Maximum Mark</th>
                      <th>Obtained Mark</th>
                    </tr>
                    <?php
                    $resultQuery="SELECT m.maximumMark AS mm,m.obtainedMark AS om,e.examId AS eid,e.examTitle AS et,e.examDateTime AS ed,e.totalQuestion AS tq FROM marK m,examination e,candidate c WHERE m.candidateId='".$candidateId."' AND m.candidateId=c.candidateId AND m.examId=e.examId";
                    $resultResult=mysqli_query($con,$resultQuery) or die('Error to send query');
                    if(mysqli_num_rows($resultResult)>0){
                      while($resultRow=mysqli_fetch_assoc($resultResult)){
                        echo "<tr>
                                  <td><a href='exam-result.php?examId=".$resultRow['eid']."'>".$resultRow['et']."</td>
                                  <td>".$resultRow['ed']."</td>
                                  <td>".$resultRow['tq']."</td>
                                  <td>".$resultRow['mm']."</td>
                                  <td>".$resultRow['om']."</td></tr>";
                      }
                    }
                    else{
                      echo "<tr><td colspan='7' style='text-align:center;'>You haven't completed any exam.</td></tr>";
                    }


                    ?>

                </table>
            </div>
          </div>
          <footer>
            <div class="footer">
              <p><a href="mailto:yafetberhanu3@gmail.com">Contact</a> | <a href="#">About</a></p>
              <p>&copy;Copyright 2021 All right reserved.</p>
            </div>
          </footer>
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
            function openTab(evt,tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

               document.getElementById(tabName).style.display="block";
                evt.currentTarget.className += " active";
            }
            function openTab2(evt,tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent_2");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks_2");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
               document.getElementById(tabName).style.display="block";
                evt.currentTarget.className += " active";
            }
            function showFilter(evt,val){
                var filterBox;
                filterBox=document.getElementById(val);
                if(filterBox.style.display==="flex"){
                    filterBox.style.display='none';
                    evt.currentTarget.childNodes[1].className="fa fa-angle-down";

                }
                else{
                    filterBox.style.display='flex';
                    filterBox.style.flexDirection='row';
                    evt.currentTarget.childNodes[1].className="fa fa-angle-up";
                }

            }
            function innerTab(val){
                document.getElementById(val).click();

            }
            function deleteRow(val,tabName){
                var target;
                target=document.getElementById(val);
                document.getElementById(tabName).deleteRow(target.rowIndex);
            }

            dropbtn.addEventListener('click',showDrop,false);
            document.addEventListener("DOMContentLoaded", function(event) {
                document.getElementById('userTab').click();
            });
          </script>

        </body>
</html>
