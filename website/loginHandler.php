<html>
	<head>
	<title>Form</title>
	</head>

	<body>

	<?php
	echo "made it 1";
	include 'library.php';
	$username = $_POST["username"];
	$password = $_POST["password"];
	echo "made it 3";
	if(logIn($username, $password)){
		echo "<h1>Hello " . $_POST["username"] . "</h1>";
	} else {
		echo "Fail :(";
	}

	?>

	</body>
	</html>
	