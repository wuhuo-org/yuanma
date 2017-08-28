<?php
function db_connect(){
	$db = new mysqli('127.0.0.1', 'user_name', 'database_password', 'database_name');
	return $db;
}
?>