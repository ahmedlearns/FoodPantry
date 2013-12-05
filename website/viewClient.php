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
	$cid = $_SESSION["cid"];
	echo "Let's display client info maybe? This could be viewed from the search thing too when you click on a client.";
	echo "\n\n Current client id is {$cid}";
	


	?>
	<!-- <meta http-equiv="refresh" content="0;url=home.html"> -->

	</body>
</html>
	