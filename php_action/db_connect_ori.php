<?php 	

$localhost = "127.0.0.1:77"; // mySql 포트를 써야함
$username = "jaewonks";
$password = "Kk052614..";
$dbname = "newnetbirds";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>