<?php if ($include) {

if (isset($_POST['delete'])) {
   $goalId = $_POST['goalId'];

   $query = constructDelete();
   for ($i = 0; $i < count($goalId); $i++) {
      $update = "UPDATE Accounts SET goalID = NULL WHERE goalId = " . clean($goalId[$i]);
      mysql_query($update);
   }

   mysql_query($query);

   if (mysql_errno() == 0)
      notifyMsg("Selected goals deleted.");
   else
      errorMsg("Failed to delete goals.");
}

?>
 
<h2 class="AverageSans">Recent Goals</h2>

<!-- <div class="four fifths"> -->

<form method="post">

<table>
   <thead style="font-size: 12px;">
      <tr>
         <th></th>
         <th>Account</th>
         <th>Amount</th>
         <th>Start Date</th>
         <th>End Date</th>
         <th></th>
      </tr>
   </thead>
   <tbody>
<?php

$select = "SELECT g.goalId, accName, g.accId, g.amount, startDate, endDate FROM Goals g, Accounts a WHERE g.accId = a.accId AND a.userId = " . $_SESSION['this_id'];
// $where = constructTransactionsWhere() . " AND t.category = c.catId AND a.accId = t.accId AND a.userId = " . $_SESSION['this_id'];
// $order = constructOrder();
// $limit = " LIMIT 30";
$query = $select; // . $where . $order . $limit;
 
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $goalId = $row['goalId'];
   $accName = $row['accName'];
   $amount = $row['amount'];
   $startDate = $row['startDate'];
   $endDate = $row['endDate'];
   $default = getDefaultGoal($row['accId']);
?>
      <tr>
         <td><input type="checkbox" name="goalId[]" value="<?php echo $goalId; ?>"></td>
         <td><?php echo $accName . defaultGoalFlag($default, $goalId); ?></td>
         <td><?php echo amtToStr($amount); ?></td>
         <td><?php echo $startDate; ?></td>
         <td><?php echo $endDate; ?></td>
         <td><a href="?p=goals&amp;a=edit&amp;id=<?php echo $goalId; ?>"><i class="icon-edit"></i></a></td>
      </tr>
<?php } ?>

   </tbody>
</table>

<?php if (mysql_num_rows($result) == 0) { ?>
<p class="padded">No goals to show.</p>
<?php } ?>

<input type="submit" name="delete" value="Delete Selected" class="gap-top" />

</form>

<!-- </div> -->

<?php } ?>