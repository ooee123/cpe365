<?php

/*
 * CONSTANTS
 */

define("MAX_LEN", 32);

/*
 * FORMATTING
 */

function getName() {
   $query = sprintf("SELECT firstName, lastName, nickName FROM Users WHERE id = '%d'",
    $_SESSION['this_id']);
   $result = mysql_query($query);

   while ($row = mysql_fetch_array($result)) {
      $firstName = $row['firstName'];
      $lastName = $row['lastName'];
      $nickName = $row['nickName'];
   }

   if ($nickName) {
      return $nickName;
   }
   else {
      return $firstName . ' ' . $lastName;
   }
}

function errorMsg($msg) {
   echo "<p class=\"error message gapped triple\">" . $msg . "</p>";

   return true;
}

function amtToStr($cents) {
   $str = sprintf("$%.2f", abs($cents / 100));

   if ($cents < 0)
      return "-" . $str;
   else
      return $str;
}

function amtToStrColor($cents) {
   if ($cents < 0)
      return "<span class=\"red\">" . amtToStr($cents) . "</span>";
   else
      return amtToStr($cents);
}

/*
 * UTILITIES
 */

function clean($str) {
   return mysql_real_escape_string(stripslashes($str));
}

function validUserName($userName) {
   $query = sprintf("SELECT userName FROM Users WHERE userName = '%s'", $userName);
   $result = mysql_query($query);

   return mysql_num_rows($result) == 0;
}

function checked($cur, $toCheck) {
   if ($cur == $toCheck) {
      echo "checked=\"checked\"";
   }
}

function constructInsert($table, $attArray, $typeArray) {
   $validAttArray = array();
   $validTypeArray = array();

   for ($i = 0; $i < count($attArray); $i++) {
      echo '(' . $_POST[$attArray[$i]] . ')';
      if (!empty($_POST[$attArray[$i]])) {
         array_push($validAttArray, $attArray[$i]);
         array_push($validTypeArray, $typeArray[$i]);
      }
   }

   $insert = "INSERT INTO " . $table  . " (" . formatAttributes($validAttArray) . ") ";
   $value = "VALUE (" . formatValues($validAttArray, $validTypeArray) . ")";
   $query = $insert . $value;

   return $query;
}

function formatAttributes($attArray) {
   $query = "";
   $numArgs = count($attArray);

   for ($i = 0; $i < $numArgs; $i++) {
      $query = $query . $attArray[$i];

      if ($i != $numArgs - 1) { 
         $query = $query . ",";
      }
   }

   return $query;
}

function formatValues($attArray, $typeArray) {
   $query = ""; 
   $numArgs = count($attArray);

   for ($i = 0; $i < $numArgs; $i++) { 
      switch ($typeArray[$i]) {
      case "string":
         $query = $query . "'" . clean($_POST[$attArray[$i]]) . "'";
         break; 
      case "password": 
         $query = $query . "'" . md5($_POST[$attArray[$i]]) . "'"; 
         break; 
      case "date": 
         $query = $query . "DATE('" . clean($_POST[$attArray[$i]]) . "')"; 
         break; 
      case "transaction": 
         $amount = clean($_POST[$attArray[$i]]);
         $transaction = clean($_POST["transaction"]);

         if ($transaction == "withdrawal") { 
            $amount = -1 * $amount; 
         }
         
         $query = $query . $amount; 
         break;
      default: 
         $query = $query . clean($_POST[$attArray[$i]]);
         break; 
      }

      if ($i != $numArgs - 1) { 
         $query = $query . ","; 
      } 
   }

   return $query; 
}

/*
 * TRANSACTION FILTER
 */

function constructWhere() {
   $fMonth = clean($_GET['month']);
   $fYear = clean($_GET['year']);
   $fAmount = clean($_GET['amt']);
   $fCategory = clean($_GET['cat']);
   $fType = clean($_GET['type']);
   $fDesc = clean($_GET['desc']);

   $where = " WHERE t.accId IN (SELECT accId FROM Accounts WHERE userId = " . $_SESSION['this_id'] . ")";
   $where = $where . whereAccId($fAccount);
   $where = $where . whereMonth($fMonth, "payDate");
   $where = $where . whereTransaction($fType);
   $where = $where . whereAmount($fAmount);
   $where = $where . whereCategory($fCategory);
   $where = $where . whereYear($fYear, "payDate");
   $where = $where . whereDescription($fDesc);

   return $where;
}

function constructOrder() {
   $fOrder = clean($_GET['order']);

   if (isset($fOrder)) {

   }
   else {
      return " ORDER BY payDate DESC";
   }
}

function whereAccId($fAccount) {
   if (!empty($fAccount) && $fAccount != "Any")
      return " AND accId = " . $fAccount;
}

function whereDescription($fDesc) {
   if (!empty($fDesc))
      return " AND (description LIKE '%" . $fDesc . "%' OR paidToFrom LIKE '%" . $fDesc . "%')";
}

function whereYear($fYear, $date) {
   if (!empty($fYear))
      return " AND YEAR(" . $date . ") = " . $fYear;
}

function whereMonth($fMonth, $date) {
   if (!empty($fMonth) && $fMonth != "Any")
      return " AND MONTHNAME(" . $date . ") = '" . $fMonth . "'";
}

function whereAmount($fAmount) {
   if (!empty($fAmount)) {
      sscanf($fAmount, "%d.%d", $dollars, $cents);
      return " AND ABS(amount) >= " . ($dollars * 100 + $cents);
   }
}

function whereCategory($fCategory) {
   if (!empty($fCategory) && $fCategory != 0)
      return " AND t.category = " . $fCategory;
}

function whereTransaction($fType) {
   if (!empty($fType)) {
      if ($transaction == "withdrawals" || $transaction == "withdrawal")
         return " AND amount < 0";
      else if ($transaction == "deposits" || $transaction == "deposit")
         return " AND amount > 0";
   }
}

?>