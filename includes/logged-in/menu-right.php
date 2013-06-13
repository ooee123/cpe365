<?php if ($include) { ?>

<div class="one fifth pad-left">

<form method="post">
   <h4 class="AverageSans">Account</h3>
   <select name="accId" class="gap-bottom">
      <option>All</option>
<?php

$query = "SELECT accId, accName FROM Accounts WHERE userId = " . $_SESSION['this_id'];
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $id = $row['accId'];
   $name = $row['accName'];
?>
      <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php } ?>

   </select>

   <h4 class="AverageSans">Transactions</h4>
   <input type="radio" name="transaction" value="both" id="both" checked="checked" /> <label for="both" class="inline">Both</label><br />
   <input type="radio" name="transaction" value="deposits" id="deposits" /> <label for="deposits" class="inline">Deposits</label><br />
   <input type="radio" name="transaction" value="withdrawals" id="withdrawals" /> <label for="withdrawals" class="inline">Withdrawals</label><br />

</form>

</div>

<?php } ?>