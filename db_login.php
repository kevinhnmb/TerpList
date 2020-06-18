<?php
	$host = "localhost";
	$user = "dbuser";
	$password = "goodbyeWorld";
	$database = "terpslistaccounts";	

	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}
?>