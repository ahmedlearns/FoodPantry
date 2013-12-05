<?php
  ini_set("display_errors", "on");
            error_reporting(E_ALL);
            include "_header.php";
?>

<!DOCTYPE html>
<html>
	<head>
	<title>Form</title>
	</head>

	<body>

	<?php
	$toMake[0] = NULL;
	$toMake[1] = $_POST["fname"];
	$toMake[2] = $_POST["lname"];
	$toMake[3] = $_POST["phone"];
	$toMake[4] = $_POST["gender"];
	$toMake[5] = $_POST["dob"];
	$toMake[6] = date("Y-m-d");
	$toMake[7] = $_POST["pday"];
	$toMake[8] = $_POST["bagid"];
	$toMake[9] = $_POST["street"];
	$toMake[10] = $_POST["city"];
	$toMake[11] = $_POST["state"];
	$toMake[12] = $_POST["zip"];
	$toMake[13] = NULL;

	$finAid = $_POST["finaid"];
	$cid = addClient($toMake, $finAid);

	$_SESSION["cid"] = $cid;

	redirect("viewClient.php");
	


	?>
	<!-- <meta http-equiv="refresh" content="0;url=home.html"> -->

	</body>
</html>
	