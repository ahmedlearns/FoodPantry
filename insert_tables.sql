--User related stuff--
----------------------
--Login user
$username = getUserName();
$password = hash(getPassword());

$result = sql(SELECT password FROM user WHERE username = $username);

if ($result not empty) {
  if($password == $result["password"]) loginOK();
}


--Client Related Stuff--
------------------------
--New Client
$firstName = getFirstName();
$lastName = getLastName();
$gender = getGender();
$dob = getDOB();
$phoneNumber = getNumber();
$street = getStreet();
$aptNum = getAptNum();
$city = getCity();
$state = getState();
$zip = getZip();
$bag = $getBag();
$startDate = getStartDate();
$pickupDay = getPickupDate();
$finAid = getFinaids();
//$delivery = getDelivery();

sql(INSERT INTO client VALUES(null, $firstName, $lastName, $number, $gender, $dob, $startDate, $pickupDate, $bag, $street, $city, $state, $zip, $aptNum));
$cid = sql(SELECT cid FROM client WHERE fname = $firstName, lname = $lastName, phone = $phoneNumber);
foreach ($finAid as $source)
  sql(INSERT INTO finaid VALUES($cid, $source));


--Bag Related Stuff--
---------------------

--Product related stuff--
-------------------------
