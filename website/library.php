<?php

  function logIn($username, $password) {
    $query = $db->query("SELECT * FROM user WHERE username = $username");
    $result = $query->fetch();
    
    if ($password == $result['password']) {
      $_SESSION['userdata'] = $result[0];
      return true;
    }
    return false;
  }
  
  function logOut() {
    session_destroy();
  }

  function addClient($clientArray, $finaidArray) {
    $insertQuery = $db->prepare("INSERT INTO client VALUES(".str_repeat("?, ", count($clientArray) - 1)." ?)");
		$insertQuery->execute($clientArray);
		
    $query = $db->query("SELECT cid FROM client WHERE fname =".$clientArray['fname']. 
        ", lname =".$clientArray['lname'].", phone = ".$clientArray['phone']);
    $cid = ($query->fetch())['cid'];
        
    foreach ($finaidArray as $source)
      $db->query("INSERT INTO finaid VALUES($cid, $source)");
  }
  
  function searchClient($lname, $phone) {
    $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE c.lname = $lastName OR c.phone = $phoneNumber GROUP BY c.cid");
    return $query->fetchAll();
  }
  
  //MembersArray {{fname,lname,dob,gender},{fname,lname,dob,gender}}
  function addFamily($membersArray, $cid) {
    $query->prepare("INSERT INTO family VALUES ($cid, ?, ?, ?, ?)")
    foreach ($membersArray as $famMember){
        $db->execute($famMember);
    }
  }
  
  function listBags() {
    $query = $db->query("SELECT b.name, SUM(c.qty * p.cost) AS cost, SUM(qty) AS numItems, numClients FROM contents c JOIN bag b ON c.bagid = b.bagid INNER JOIN product p ON c.prodid = p.prodid LEFT JOIN (SELECT bagid, COUNT(ISNULL(*)) AS numClients FROM client GROUP BY bagid) AS cl ON cl.bagid = b.bagid GROUP BY b.bagid");
    return $query->fetchAll();
  }
  
  function viewBag($bagid) {
    $query = $db->query();
    return $query->fetchAll();
  
  }
  
  function saveBag($bagid, $contentsArray) {
    for ($contentsArray as $item) {
      $prodid = $item['prodid'];
      $quantity = $item['quantity'];
      if($quantity == 0  && $prodid != null){
          $db->query("DELETE * FROM contents WHERE bagid = $bagid AND prodid = $prodid");
      } elseif($prodid != null) {
          $db->query("UPDATE contents SET prevqty = qty, qty = $quantity WHERE bagid = $bagid AND prodid = $prodid");
      } else {
          $db->query("INSERT INTO contents VALUES($bagid, $prodid, $quantity, 0)");
      }
    }
  }
  
  function listProducts() {
    $query = $db->query("SELECT name, COALESCE(dqty, 0) - COALESCE(pqty, 0) AS total FROM product p LEFT JOIN (SELECT prodid, SUM(qty) AS pqty FROM pickup pu JOIN contents c ON pu.bagid = c.bagid GROUP BY prodid) AS pu ON p.prodid = pu.prodid LEFT JOIN (SELECT prodid, SUM(qty) AS dqty FROM dropoff GROUP BY prodid) AS d ON p.prodid = d.prodid");
    return $query->fetchAll();
  }
  
  //We control the source! a.k.a have a dropdown
  function addProduct($name, $cost, $sourceid) {
    $db->query("INSERT INTO product VALUES(null, $name, $cost, $sourceid)");
  }

  function monthlyServiceReport() {
    $query = $db->query("SELECT CEIL(pday/7) AS week, COUNT(c.cid) AS house, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64, COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total, SUM(cost) FROM client c LEFT JOIN (SELECT cid, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64 FROM family f GROUP BY cid ) f ON c.cid = f.cid JOIN baginfo b ON c.bagid = b.bagid GROUP BY CEIL(pday/7)");
    return $query->fetchAll();
  }
  
  function lastServiceReport() {
    $query = $db->query("SELECT CEIL(pday/7) AS week, COUNT(c.cid) AS house, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64, COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total, SUM(cost) FROM client c LEFT JOIN (SELECT cid, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64 FROM family f GROUP BY cid ) f ON c.cid = f.cid JOIN oldbaginfo b ON c.bagid = b.bagid GROUP BY CEIL(pday/7);");
    return $query->fetchAll();
  }

  function listGroceries() {
    $query = $db->query("SELECT name, SUM(qty) AS qty, SUM(prevqty) AS prevqty FROM product p JOIN contents c ON p.prodid = c.prodid GROUP BY p.prodid");
    return $query->fetchAll();
  }
  
  function listPickups($day) {
    $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start, bagid, pday FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE pday = $day");
    return $query->fetchAll();
  }
  
  function clientPickupInfo($cid) {
    $query = $db->query("SELECT p.name, c.qty FROM client cl JOIN bag b on cl.bagid = b.bagid JOIN contents c ON c.bagid = cl.bagid JOIN product p ON p.prodid = c.prodid WHERE cid = $cid");
    return $query->fetch(); 
  }
  
  function completePickup($cid, $bagid, $pday) {  
    $db->query("INSERT INTO pickup VALUES(null, STR_TO_DATE('".date(m)." ".$pday." ".date(Y)."', '%m %d %Y'), $cid, $bagid);");
  }
  
  function dropoffList() {
    $query = $db->query("SELECT p.name, p.prodid, s.name, s.sourceid FROM product p JOIN source s ON p.sourceid = s.sourceid");
    return $query->fetchAll();
  }
  
  //products {{qty, sourceid, prodid},{}}
  function confirmDropoff($productArray) {
    $query = $db->prepare("INSERT INTO dropoff VALUES(null, NOW(), ?, ?, ?)");
    foreach ($productArray as $product)
        $query->execute($product);
  }

  

?>
