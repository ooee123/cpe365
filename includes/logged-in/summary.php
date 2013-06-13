<?php if ($include) {

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

$query = sprintf("SELECT * FROM Accounts WHERE userId = %d ORDER BY accId", $_SESSION['this_id']);
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $name = $row['accName'];
   $balance = getBalance($row['accId']);
   $total = $total + $balance;

?>

      <tr>
         <td><a href="?p=transactions&amp;sort=account&amp;q=1"><?php echo $name; ?></a></td>
         <td><?php echo amtToStrColor($balance); ?></td>
         <td></td>
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