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

   $row = mysql_fetch_array($result);

   $firstName = $row['firstName'];
   $lastName = $row['lastName'];
   $nickName = $row['nickName'];

   if ($nickName)
      return $nickName;
   else
      return $firstName . ' ' . $lastName;
}

function getAccount($id) {
   $query = sprintf("SELECT accId, accName FROM Accounts WHERE userId = %d AND accId = %d",
    $_SESSION['this_id'], $id);
   $result = mysql_query($query);

   $row = mysql_fetch_array($result);

   return $row['accName'];
}

function errorMsg($msg) {
   echo "<p class=\"error message gapped triple\">" . $msg . "</p>";

   return true;
}

function notifyMsg($msg) {
   echo "<p class=\"success message gapped triple\">" . $msg . "</p>";

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

function selected($cur, $toCheck) {
   if ($cur == $toCheck) {
      echo "selected";
   }
}

function constructInsert($table, $attArray, $typeArray) {
   $validAttArray = array();
   $validTypeArray = array();

   for ($i = 0; $i < count($attArray); $i++) {
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
         sscanf($amount, "%d.%d", $dollars, $cents);
         $amount = $dollars * 100 + $cents;
         $transaction = isset($_POST['transaction']) ? clean($_POST["transaction"]) : NULL;
 
         if ($transaction == "withdrawal" || $transaction == "withdrawals") { 
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

function constructUpdate($table, $attArray, $typeArray) {
   $queries = array();
   $update = "UPDATE " . $table;
   $where = " WHERE id = ";

   for ($i = 0; $i < count($attArray); $i++) {
      if (!empty($_POST[$attArray[$i]])) {
         $set = constructSet($attArray[$i], $typeArray[$i]);
         $where = " WHERE id = " . $_SESSION['this_id'];
         array_push($queries, $update . $set . $where);
      }
   }

   return $queries;
}

function constructSet($att, $type) {
   if ($type == "date") {
      return " SET " . $att . " = DATE('" . $_POST[$att] . "')";
   }
   else if ($type == "string") {
      if (empty($_POST[$att])) {
         return " SET " . $att . " = NULL";
      }
      return " SET " . $att . " = '" . $_POST[$att] . "'";
   }
   else if ($type == "category") {
      return " SET " . $att . " = " . $_POST[$att];
   }
   else if ($type == "transaction") {
      $amount = clean($_POST[$att]);
      sscanf($amount, "%d.%d", $dollars, $cents);
      $amount = $dollars * 100 + $cents;
      $transaction = isset($_POST['transaction']) ? clean($_POST["transaction"]) : NULL;
       
      if ($transaction == "withdrawal" || $transaction == "withdrawals") {
         $amount = -1 * $amount;
      }
      return " SET " . $att . " = " . $amount;
   }
   return " SET " . $att . " = " . $_POST[$att];
}

/*
 * TRANSACTIONS
 */

function constructDelete() {
   $delete = "DELETE FROM Transactions WHERE 0 ";
   $transId = $_POST['transId'];

   for ($i = 0; $i < count($transId); $i++) {
      $delete = $delete . " OR transId = " . $transId[$i];
   }

   return $delete;
}

function constructWhere() {
   $fAccount = clean($_GET['acc']);
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

   if (!empty($fOrder)) {

   }
   else {
      return " ORDER BY payDate DESC";
   }
}

function whereAccId($fAccount) {
   if (!empty($fAccount))
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
   if (!empty($fMonth))
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
      if ($fType == "withdrawals" || $fType == "withdrawal")
         return " AND amount < 0";
      else if ($fType == "deposits" || $fType == "deposit")
         return " AND amount > 0";
   }
}

?>