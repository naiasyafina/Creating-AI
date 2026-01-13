<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "";
$db = "uaspbw"; //nama database - UPDATED

//create connection
$conn = new mysqli($servername,$username,$password,$db);

//check apakah ada error connection
if($conn->connect_error){
	//jika ada, hentikan script dan tampilkan pesan error
	die("Connection failed : ".$conn->connect_error);
}

//echo "Connected successfully<hr>";
?>