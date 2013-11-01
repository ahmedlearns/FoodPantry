--User related stuff--
----------------------
--Login user
$username = getUserName();
$password = hash(getPassword());

$result = sql(SELECT password FROM user WHERE username = $username);

if ($result not empty) {
    if($password == hash($result["password"])) goToMainMenu();
}
echo "Username and password do not match";


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

function onSave() {
    sql(INSERT INTO client VALUES(null, $firstName, $lastName, $number, $gender, $dob, $startDate, $pickupDate, $bag, $street, $city, $state, $zip, $aptNum));
    $cid = sql(SELECT cid FROM client WHERE fname = $firstName, lname = $lastName, phone = $phoneNumber);
    foreach ($finAid as $source)
      sql(INSERT INTO finaid VALUES($cid, $source));
      goToAddFamily();
}



-- Search Client
$lastName = getLastName();
$phoneNumber = getNumber();

$num_members = sql(SELECT COUNT(*) AS size FROM family GROUP BY cid);
$cid = sql(
    SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start
    FROM client c LEFT JOIN family f ON c.cid = f.cid
    WHERE c.lname = $lastName OR c.phone = $phoneNumber
    GROUP BY c.cid;
);

-- Add Family
$members = getMembers();
$cid = getCid();

function onSave() {
    foreach ($members as $famMember){
        sql(INSERT INTO family VALUES ($cid, $famMember["FirstName"], $famMember["LastName"], $famMember["DateofBirth"], $famMember["Gender"]));
    }
    goToMainMenu();
}

--Bag Related Stuff--
---------------------
-- Bag List
$all_bags = sql(
    SELECT b.name,
         SUM(c.qty * p.cost) AS cost,
         SUM(qty) AS numItems,
         numClients
    FROM contents c
    JOIN bag b ON c.bagid = b.bagid
    INNER JOIN product p ON c.prodid = p.prodid
    LEFT JOIN
    (SELECT bagid,
            COUNT(ISNULL(*)) AS numClients
    FROM client
    GROUP BY bagid) AS cl ON cl.bagid = b.bagid
    GROUP BY b.bagid;);

$viewEditBag = getBag(); -- $viewEditBag will store the bagid of the bag to view or edit

-- Edit Bag
$bag_list = sql(SELECT p.name AS "Product Name", c.qty AS "Quantity", prodid 
    FROM contents c
    INNER JOIN  product p ON  p.prodid = c.prodid
    WHERE c.bagid = $viewEditBag
);

function onSaveClick() {
    $productID = getProductID();
    $productName = getProductName();
    $productQuant = getProductQuant();
    if($productQuant == 0  && $productID != null){
        sql(DELETE * FROM contents WHERE bagid = $viewEditBag AND prodid = $productID);
    } elseif($productID != null) { -- (User edited the quantity of a product that's already there or a new product)
        sql(UPDATE contents SET name = $productName, prevqty = qty, qty = $productQuant);
    } else {
        sql(INSERT INTO contents VALUES($viewEditBag, $productID, $productQuant, 0));
    }
}

--Product related stuff--
-------------------------
-- List All Products
$allProducts = sql(
    SELECT name, COALESCE(dqty, 0) - COALESCE(pqty, 0) AS total
    FROM product p
    LEFT JOIN
    (SELECT prodid, SUM(qty) AS pqty
     FROM pickup pu 
     JOIN contents c ON pu.bagid = c.bagid GROUP BY prodid) AS pu
    ON p.prodid = pu.prodid
    LEFT JOIN
    (SELECT prodid, SUM(qty) AS dqty
     FROM dropoff GROUP BY prodid) AS d
    ON p.prodid = d.prodid;
);

-- Add new Product
$name = getName();
$cost = getCost();
$source = getSource();

$sourceID = sql(SELECT sourceid FROM source WHERE name = $source);

if ($sourceID is null) {--make new source
    sql(INSERT INTO source VALUES(null, $source));
    $sourceID = sql(SELECT MAX(sourceid) FROM source); --get latest source
}

sql(INSERT INTO product VALUES(null, $name, $cost, $sourceID));

--Report related stuff--
------------------------

-- Monthly service report
$activeReportData = sql(
    SELECT CEIL(pday/7) AS week,
           COUNT(c.cid) AS house,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64,
           COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total,
           SUM(cost)
    FROM client c
    LEFT JOIN
      (SELECT cid,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64
       FROM family f
       GROUP BY cid ) f ON c.cid = f.cid
    JOIN baginfo b ON c.bagid = b.bagid
    GROUP BY CEIL(pday/7);
);
  
$lastReportData = sql(
    SELECT CEIL(pday/7) AS week,
           COUNT(c.cid) AS house,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64,
           SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64,
           COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total,
           SUM(cost)
    FROM client c
    LEFT JOIN
      (SELECT cid,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64,
              SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64
       FROM family f
       GROUP BY cid ) f ON c.cid = f.cid
    JOIN oldbaginfo b ON c.bagid = b.bagid
    GROUP BY CEIL(pday/7);
);


-- Grocery List
$groceryList = sql(
    SELECT name, SUM(qty) AS qty, SUM(prevqty) AS prevqty
    FROM product p JOIN contents c ON p.prodid = c.prodid 
    GROUP BY p.prodid;
);



--Pickup/Dropoff stuff--
------------------------

-- Pickup
$dayOfMonth = getDayOfMonth();
$pickupsToday = sql(
    SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start, bagid, pday
    FROM client c LEFT JOIN family f ON c.cid = f.cid
    WHERE pday = $dayOfMonth;
);

function onReturn() {
    goToMainMenu();
}

function onSignIn() {
    $clientInfo = getSelectedClient();
    goToPickupConfirm($clientInfo);
}


-- Pickup Confirmation
$clientInfo = getClientInfo();

$pickupData = sql(
    SELECT p.name, c.qty 
    FROM client cl JOIN bag b on cl.bagid = b.bagid
    JOIN contents c ON c.bagid = cl.bagid
    JOIN product p ON p.prodid = c.prodid 
    WHERE cid = $clientInfo["cid"];
);

function onComplete() {
    -- note date is a php function
    sql(INSERT INTO pickup VALUES(null, STR_TO_DATE(date(m)+" "$clientInfo["pday"]+" "+date(Y), "%m %d %Y"), $clientInfo["cid"], $clientInfo["bagid"]););
    goToPickups();
}

function onReturn() {
    goToMainMenu();
}


-- Drop Off
$productList = sql(SELECT p.name, p.prodid, s.name, s.sourceid FROM product p JOIN source s ON p.sourceid = s.sourceid;);
--list is shown to users with quatity editable
$dropoffs = getDropoffs();
foreach ($dropoffs as $dropoff)
    sql(INSERT INTO dropoff VALUES(null, NOW(), $dropoff["qty"], $dropoff["sourceid"], $dropoff["prodid"]););







