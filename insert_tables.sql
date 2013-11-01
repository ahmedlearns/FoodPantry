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
$cid = sql(SELECT lname, fname, size, address, phone, start FROM client INNER JOIN $num_members ON $num_members.cid = client.cid WHERE lname = $lastName OR phone = $phoneNumber);

-- Add Family
$members = getMembers();
$cid = getCid();

if(isClicked(save_button)){
    foreach ($members as $famMember){
        sql(INSERT INTO family VALUES ($cid, $famMember["FirstName"], $famMember["LastName"], $famMember["DateofBirth"], $famMember["Gender"]));
    }
}

goToMainMenu();

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
    SELECT name, dqty - pqty AS total
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


-- Monthly service report













