<?php
ini_set("display_errors", "on");
	error_reporting(E_ALL);
	include "_header.php";
?>
<!DOCTYPE html>
<html>
<body>
	<?php
		$lname = $_POST["lName"];
		$phone = $_POST["number"];
		$allClients = searchClient($lname, $phone);
		echo "evan, you need to make a pretty table with client info. yay";


	?>

</body>


</html>