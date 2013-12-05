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
	$name = $_POST["name"];
	$source = $_POST["source"];
	$cost = $_POST["cost"];
	addProduct($name, $cost, $source);
	redirect("productHome.html");
	


	?>
	<!--<meta http-equiv="refresh" content="0;url=home.html">-->

	</body>
</html>