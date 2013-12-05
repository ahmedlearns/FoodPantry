<?php

  function logIn($username, $password) {
    global $db;
    $query = $db->query("SELECT * FROM user WHERE username = '$username'");
    if ($query != null){
      $result = $query->fetch();
      if(!empty($result)){
        if ($password == $result['password']) {
          $_SESSION['userdata'] = $result[0];
          return true;
        }
      }
    }else
        return false;
  }

  function test(){
    return "Hello";
  }
  
  function logOut() {
    session_destroy();
  }

  function addClient($clientArray, $finaidArray) {
    global $db;
    $insertQuery = $db->prepare("INSERT INTO client VALUES(".str_repeat("?, ", count($clientArray) - 1)." ?)");
		$insertQuery->execute($clientArray);
		
    $query = $db->query("SELECT cid FROM client WHERE fname ='{$clientArray[1]}' AND lname ='{$clientArray[2]}' AND phone = '{$clientArray[3]}'");
    $result = $query->fetch();
    $cid = $result['cid'];
    foreach ($finaidArray as $source){
      $db->query("INSERT INTO finaid VALUES($cid, $source)");
    }

    return $cid;
  }
  
  function searchClient($lname, $phone) {
    global $db;
    if($lname == NULL){
      $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE c.phone = '$phone' GROUP BY c.cid");
      return $query->fetchAll();
    } else if ($phone == NULL){
      $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE c.lname = '$lname' GROUP BY c.cid");
      return $query->fetchAll();
    } else{
      $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE c.lname = '$lname' OR c.phone = '$phone' GROUP BY c.cid");
      return $query->fetchAll();
    }
  }
  
  //MembersArray {{fname,lname,dob,gender},{fname,lname,dob,gender}}
  function addFamily($membersArray, $cid) {
    global $db;
    $query->prepare("INSERT INTO family VALUES ($cid, ?, ?, ?, ?)");
    foreach ($membersArray as $famMember){
        $db->execute($famMember);
    }
  }
  
  function listBags() {
    global $db;
    $query = $db->query("SELECT b.name, SUM(c.qty * p.cost) AS cost, SUM(qty) AS numItems, numClients FROM contents c JOIN bag b ON c.bagid = b.bagid INNER JOIN product p ON c.prodid = p.prodid LEFT JOIN (SELECT bagid, COUNT(*) AS numClients FROM client GROUP BY bagid) AS cl ON cl.bagid = b.bagid GROUP BY b.bagid");
    return $query->fetchAll();
  }
  
  function viewBag($bagid) {
    global $db;
    $query = $db->query();
    return $query->fetchAll();
  
  }

  function makeBagDropDown()
  {
    global $db;
    $query = $db->query("SELECT * FROM bag");
    $result= $query->fetchAll();
    $toReturn = "<select id='selectbasic' name='bagid' class='input-xlarge'>";
    foreach ($result as $currBag)
    {
      $toReturn = $toReturn . "<option value = {$currBag['bagid']}>{$currBag['name']}</option>"; 
    }
    $toReturn = $toReturn . "</select>";
    return $toReturn;
  }

  function makeSourceDropDown()
  {
    global $db;
    $query = $db->query("SELECT * FROM source");
    $result= $query->fetchAll();
    $toReturn = "<select id='selectbasic' name='source' class='input-xlarge'>";
    foreach ($result as $currSrc)
    {
      $toReturn = $toReturn . "<option value = {$currSrc['sourceid']}>{$currSrc['name']}</option>"; 
    }
    $toReturn = $toReturn . "</select>";
    return $toReturn;
  }

  function makeFinaidList()
  {
    global $db;
    $query = $db->query("SELECT aid, name FROM aidsrc");
    $result = $query->fetchAll();
    $toReturn = "<select id='selectmultiple' name='finaid[]' class='input-xlarge' multiple='multiple'>";
    foreach($result as $currAid){
      $toReturn = $toReturn . "<option value = {$currAid['aid']}>{$currAid['name']}</option>";
    }
    $toReturn = $toReturn . "</select>";
    return $toReturn;
  }

  function makeProductDropDown()
  {
    global $db;
    $query = $db->query("SELECT s.name as sname, p.name as pname, prodid FROM product p JOIN source s WHERE p.sourceid = s.sourceid");
    $result= $query->fetchAll();
    $toReturn = "<select id='selectbasic' name='prodid' class='input-xlarge'>";
    foreach ($result as $currProd)
    {
      $toReturn = $toReturn . "<option value = {$currProd['prodid']}>{$currProd['pname']} from {$currProd['sname']}</option>"; 
    }
    $toReturn = $toReturn . "</select>";
    return $toReturn;
  }

  function getBagNames()
  {
    global $db;
    
    $ind = 0;
    foreach($result as $currArr){
      $toReturn[$ind] = $currArr["name"];
      $ind++;
    }
    return $toReturn;
  }

  function getBagID($bagName)
  {
    global $db;
    $query = $db->query("SELECT bagid FROM bag WHERE bag.name = '$bagName'");
    $result= $query->fetchAll();
    $ind = 0;
    foreach($result as $currArr){
      $toReturn[$ind] = $currArr["bagid"];
      $ind++;
    }
    return $toReturn;
  }
  
  function saveBag($bagid, $contentsArray) {
    global $db;
    foreach ($contentsArray as $item) {
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
    global $db;
    $query = $db->query("SELECT name, COALESCE(dqty, 0) - COALESCE(pqty, 0) AS total FROM product p LEFT JOIN (SELECT prodid, SUM(qty) AS pqty FROM pickup pu JOIN contents c ON pu.bagid = c.bagid GROUP BY prodid) AS pu ON p.prodid = pu.prodid LEFT JOIN (SELECT prodid, SUM(qty) AS dqty FROM dropoff GROUP BY prodid) AS d ON p.prodid = d.prodid");
    return $query->fetchAll();
  }
  
  //We control the source! a.k.a have a dropdown
  function addProduct($name, $cost, $sourceid) {
    global $db;
    $db->query("INSERT INTO product VALUES(null, '$name', '$cost', '$sourceid')");
  }

  function monthlyServiceReport() {
    global $db;
    $query = $db->query("SELECT CEIL(pday/7) AS week, COUNT(c.cid) AS house, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64, COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total, SUM(cost) FROM client c LEFT JOIN (SELECT cid, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64 FROM family f GROUP BY cid ) f ON c.cid = f.cid JOIN baginfo b ON c.bagid = b.bagid GROUP BY CEIL(pday/7)");
    return $query->fetchAll();
  }
  
  function lastServiceReport() {
    global $db;
    $query = $db->query("SELECT CEIL(pday/7) AS week, COUNT(c.cid) AS house, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) < 18 THEN 1 ELSE 0 END) + SUM(COALESCE(f.U18, 0)) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.18TO64, 0)) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(c.dob) > 64 THEN 1 ELSE 0 END) + SUM(COALESCE(f.O64, 0)) AS O64, COUNT(c.cid) + SUM(COALESCE(f.U18, 0)) + SUM(COALESCE(f.18TO64, 0)) + SUM(COALESCE(f.O64, 0)) AS total, SUM(cost) FROM client c LEFT JOIN (SELECT cid, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) < 18 THEN 1 ELSE 0 END) AS U18, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS 18TO64, SUM(CASE WHEN YEAR(NOW())-YEAR(f.dob) > 64 THEN 1 ELSE 0 END) AS O64 FROM family f GROUP BY cid ) f ON c.cid = f.cid JOIN oldbaginfo b ON c.bagid = b.bagid GROUP BY CEIL(pday/7);");
    return $query->fetchAll();
  }

  function listGroceries() {
    global $db;
    $query = $db->query("SELECT name, SUM(qty) AS qty, SUM(prevqty) AS prevqty FROM product p JOIN contents c ON p.prodid = c.prodid GROUP BY p.prodid");
    return $query->fetchAll();
  }
  
  function listPickups($day) {
    global $db;
    $query = $db->query("SELECT c.lname, c.fname, COUNT(f.cid) + 1 AS size, street, city, state, zip, apt, phone, start, bagid, pday FROM client c LEFT JOIN family f ON c.cid = f.cid WHERE pday = $day");
    return $query->fetchAll();
  }
  
  function clientPickupInfo($cid) {
    global $db;
    $query = $db->query("SELECT p.name, c.qty FROM client cl JOIN bag b on cl.bagid = b.bagid JOIN contents c ON c.bagid = cl.bagid JOIN product p ON p.prodid = c.prodid WHERE cid = $cid");
    return $query->fetch(); 
  }
  
  function completePickup($cid, $bagid, $pday) {  
    global $db;
    $db->query("INSERT INTO pickup VALUES(null, STR_TO_DATE('".date(m)." ".$pday." ".date(Y)."', '%m %d %Y'), $cid, $bagid);");
  }
  
  function dropoffList() {
    global $db;
    $query = $db->query("SELECT p.name, p.prodid, s.name, s.sourceid FROM product p JOIN source s ON p.sourceid = s.sourceid");
    return $query->fetchAll();
  }
  
  //products {{qty, sourceid, prodid},{}}
  function confirmDropoff($productArray) {
    global $db;
    $query = $db->prepare("INSERT INTO dropoff VALUES(null, NOW(), ?, ?, ?)");
    foreach ($productArray as $product)
        $query->execute($product);
  }

  //****************************************************************************
  //***************** Useful Scripts not neccisarily databases *****************
  //****************************************************************************
  function redirect($url, $cond = true, $die=true) {
    if ($cond) {
      header("Location: $url");
      if ($die) die();
    }
  }

  function setError($message){
    $_SESSION["error"] = $message;
  }

  function getError(){
    $temp = $_SESSION["error"];
    unset($_SESSION["error"]);
    return $temp;
  }
?>
