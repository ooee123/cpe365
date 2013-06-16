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

function defaultGoalFlag($default, $id) {
   if ($default == $id)
      return " <strong>[Primary]</strong>";
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

/*
 * SUMMARY
 */

function getAccType($accId) {
   $query = "SELECT accType FROM Accounts WHERE accId = " . $accId;
   $result = mysql_query($query);
   $row = mysql_fetch_array($result);

   return $row['accType'];
}

function getProgress($accId, $accType) {
   $where = " WHERE t.payDate BETWEEN g.startDate AND g.endDate AND t.accId = a.accId AND g.goalId = a.goalId AND a.accId = " . $accId;
   $from = " FROM Accounts a, Goals g, Transactions t";

   if ($accType == "checking") {
      $select = "SELECT ABS(SUM(t.amount)) AS progress";
      $where = $where . " AND t.amount < 0";
      $query = $select . $from . $where;
      $result = mysql_query($query);

      $row = mysql_fetch_array($result);

      return $row['progress'];
   }
   else {
      $select = "SELECT SUM(t.amount) AS progress";
      $query = $select . $from . $where;
      $result = mysql_query($query);

      $row = mysql_fetch_array($result);

      return $row['progress'];
   }
}

function getDefaultGoal($accId) {
   $select = "SELECT Accounts.goalId";
   $from = " FROM Accounts, Goals";
   $where = " WHERE Accounts.goalId = Goals.goalId AND Accounts.accId = " . $accId;
   $query = $select . $from . $where;
   $result = mysql_query($query);

   $row = mysql_fetch_array($result);

   return $row['goalId'];
}
 
function getGoal($accId) {
   $select = "SELECT amount";
   $from = " FROM Accounts, Goals";
   $where = " WHERE Accounts.goalId = Goals.goalId AND Accounts.accId = " . $accId;
   $query = $select . $from . $where;
   $result = mysql_query($query);

   $row = mysql_fetch_array($result);

   if (mysql_num_rows($result) == 0)
      return 0;
   else
      return $row['amount'];
}

function reportProgress($progress, $goal, $accType) {
   if ($accType == "checking" && $progress > $goal) {
      $report = "<span class=\"red\"><strong>" . amtToStr($progress) . "</strong></span>";
   }
   else if ($accType == "savings" && $progress >= $goal) {
      $report = "<span class=\"green\"><strong>" . amtToStr($progress) . "</strong></span>";
   }
   else {
      $report = amtToStr($progress);
   }

   return $report . " / " . amtToStr($goal);
}

function getBalance($accId) {
   $balance = 0;

   $query = sprintf("SELECT SUM(amount) AS total FROM Transactions WHERE accId = '%d'", $accId);
   $result = mysql_query($query);

   while ($row = mysql_fetch_array($result)) {
      $balance = $balance + $row['total'];
   }

   $query = sprintf("SELECT SUM(amount) AS total FROM Transfers WHERE transferTo = '%d'", $accId);
   $result = mysql_query($query);

   while ($row = mysql_fetch_array($result)) {
      $balance = $balance + $row['total'];
   }

   return $balance;
}

/*
 * QUERY CONSTRUCTORS
 */

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
      return " SET " . $att . " = DATE('" . clean($_POST[$att]) . "')";
   }
   else if ($type == "string") {
      if (empty($_POST[$att])) {
         return " SET " . $att . " = NULL";
      }
      return " SET " . $att . " = '" . clean($_POST[$att]) . "'";
   }
   else if ($type == "category") {
      return " SET " . $att . " = " . clean($_POST[$att]);
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
   return " SET " . $att . " = " . clean($_POST[$att]);
}

function constructWhere() {
   $fAccount = clean($_GET['acc']);
   $fMonth = clean($_GET['month']);
   $fYear = clean($_GET['year']);
   $fAmount = clean($_GET['amt']);
   $fCategory = clean($_GET['cat']);
   $fType = clean($_GET['type']);
   $fDesc = clean($_GET['desc']);

   $fDesc = str_replace('%', '\%', $fDesc);
   $fDesc = str_replace('_', '\_', $fDesc);

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
   return " ORDER BY payDate DESC";
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

function deleteAccount($accId) {
   $queries = array();

   /* Transaction deleting */
   $delete = "DELETE FROM Transactions";
   $where = " WHERE accId = " . $accId;
   $query = $delete . $where;
   array_push($queries, $query);
    
   /* Clear foreign key goalId in account */
   $update = "UPDATE Accounts";
   $set = " SET goalId = NULL";
   $query = $update . $set . $where;
   array_push($queries, $query);
    
   /* Goal deleting */
   $delete = "DELETE FROM Goals";
   $query = $delete . $where;
   array_push($queries, $query);
    
   /* Delete account */
   $delete = "DELETE FROM Accounts";
   $query = $delete . $where;
   array_push($queries, $query);
 
   return $queries;
}

?>