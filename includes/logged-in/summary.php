<?php if ($include) { ?>

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
   $accType = getAccType($row['accId']);
   $progress = getProgress($row['accId'], $accType);
   $goal = getGoal($row['accId']);
?>

      <tr>
         <td><a href="?p=transactions&amp;acc=<?php echo $id; ?>"><?php echo $name; ?></a></td>
         <td><?php echo amtToStrColor($balance); ?></td>
         <td>
<?php
if ($goal) {
?>
            <?php echo reportProgress($progress, $goal, $accType); ?>
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
         <td><?php echo amtToStrColor($total); ?></td>
         <td></td>
      </tr>
   </tbody>
</table>

<?php } ?>