<?php

$server = "localhost";
$username = "phpmyadmin";
$password = "root";
$database = "twireward";
$db = mysqli_connect($server, $username, $password, $database);

if($db == false){
   echo "An error has occurred, come back later";
   exit;
}  

mysqli_query($db, "SET NAMES 'utf8'");

session_start();

?>