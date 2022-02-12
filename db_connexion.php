<?php
function connexion(){
    $db_username = "root";
	$db_password = "";
	$conn = new PDO("mysql:host=localhost;dbname=photos", $db_username, $db_password);
	if(!$conn){
		die("Fatal Error: Connection Failed!");
	}
    return $conn;
}
?>