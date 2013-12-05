<!DOCTYPE html>
<html>
	<head>
	<title>Form</title>
	</head>

	<body>

	<?php
	ini_set("display_errors", "on");
	error_reporting(E_ALL);
	include "_header.php";
	$username = $_POST["username"];
	$password = $_POST["password"];
	redirect("home.html", logIn($username, $password));
	setError("Incorrect username or password");
	redirect("index.php");


	?>
	<!--<meta http-equiv="refresh" content="0;url=home.html">-->

	</body>
</html>
	