<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

$db_name = "ek_db";

$conn = mysqli_connect($servername, $username, $password, $db_name);

if(!$conn){
    echo "Connect failed!";
}else{
    echo " ";
}