<?php if ($include) {

function getProgress($accId) {
   $query = "SELECT accType FROM Accounts WHERE accId = " . $accId;
   $result = mysql_query($query);
   $row = mysql_fetch_array($result);
   $accType = $row['accType'];
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

?>

<h2 class="AverageSans">Summary</h2>

<table>
   <thead style="font-size: 12px;">
      <tr>
         <th>Account Name</th>
         <th>Balance</th>
         <th>Goal</th>
      </tr>
   </thead>
   <tbody>

<?php

$total = 0;

$query = sprintf("SELECT accId, accName FROM Accounts WHERE userId = %d ORDER BY accId", $_SESSION['this_id']);
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $id = $row['accId'];
   $name = $row['accName'];
   $balance = getBalance($id);
   $total = $total + $balance;
   $progress = getProgress($row['accId']);
   $goal = getGoal($row['accId']);
?>

      <tr>
         <td><a href="?p=transactions&amp;acc=<?php echo $id; ?>"><?php echo $name; ?></a></td>
         <td><?php echo amtToStrColor($balance); ?></td>
         <td>
<?php
if ($goal) {
?>
            <?php echo amtToStrColor($progress) . " / " . amtToStr($goal); ?>
<?php
}
else { ?>
            No primary goal set
<?php } ?>
         </td>
      </tr>

<?php } ?>

      <tr style="font-weight: bold;">
         <td>Total</td>
         <td><?php echo amtToStr($total); ?></td>
         <td></td>
      </tr>
   </tbody>
</table>

<?php } ?>