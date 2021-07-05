<?php

$db_host='localhost';
$db_name='onec';
$db_user='root';
$db_password='';

$conn_error="Connection failed";

if($con=mysqli_connect($db_host,$db_user,$db_password)){
	if(!mysqli_select_db($con,$db_name)){
		die ("Database not found");
	}
}else{
	die ("Database connection failed!");
}


?>
