<!DOCTYPE html>
<html>
	<head>
	<title>Form</title>
	</head>

	<body>

	<?php
	include '/library.php';
	echo getcwd();
	echo "made it 1";
	$username = $_POST["username"];
	$password = $_POST["password"];
	echo "made it 3";
	if(logIn($username, $password)){
		echo "<h1>Hello " . $_POST["username"] . "</h1>";
	} else {
		echo "Fail :(";
	}


	?>
	<!--<meta http-equiv="refresh" content="0;url=home.html">-->

	</body>
</html>
	