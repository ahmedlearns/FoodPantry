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

-- Search Client
$lastName = getLastName();
$phoneNumber = getNumber();

$num_members = sql(SELECT COUNT(*) AS size FROM family GROUP BY cid);
$cid = sql(SELECT lname, fname, size, address, phone, start FROM client INNER JOIN $num_members ON $num_members.cid = client.cid WHERE lname = $lastName OR phone = $phoneNumber GROUP BY cid);

-- Add Family
$members = getMembers();
$cid = getCid();

if(isClicked(save_button)){
    foreach ($members as $famMember){
        sql(INSERT INTO family VALUES ($cid, $members["First Name"], $members["Last Name"], $members["Date of Birth"], $members["Gender"]));
    }
}

goToMainMenu();

--Bag Related Stuff--
---------------------
-- View Bag



--Product related stuff--
-------------------------
