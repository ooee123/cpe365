<?php if ($include) { ?>

<div class="one fifth pad-left">

<form method="post">
   <h4 class="AverageSans">Account</h3>
   <select name="accId" class="gap-bottom">
      <option value="" <?php selected($fAccount, ""); ?>>Show all</option>
<?php
$query = "SELECT accId, accName FROM Accounts WHERE userId = " . $_SESSION['this_id'];
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $id = $row['accId'];
   $name = $row['accName'];
?>
      <option value="<?php echo $id; ?>" <?php selected($fAccount, $id); ?>><?php echo $name; ?></option>
<?php } ?>

   </select>

   <h4 class="AverageSans">Transactions</h4>
   <input type="radio" name="transaction" value="" id="both" <?php checked($fType, ""); ?> />
    <label for="both" class="inline">Both</label><br />
   <input type="radio" name="transaction" value="deposits" id="deposits" <?php checked($fType, "deposits"); ?>  />
    <label for="deposits" class="inline">Deposits</label><br />
   <input type="radio" name="transaction" value="withdrawals" id="withdrawals" <?php checked($fType, "withdrawals"); ?>  />
    <label for="withdrawals" class="inline">Withdrawals</label><br />

   <h4 class="AverageSans">Month</h4>
   <select name="month" class="gap-bottom">
      <option value="" <?php selected($fMonth, ""); ?>>Show all</option>
<?php
$allMonths = array("January", "February", "March", "April", "May", "June", "July",
 "August", "September", "October", "November", "December");
foreach ($allMonths as $monthName) { ?>
      <option value="<?php echo $monthName; ?>" <?php selected($fMonth, $monthName); ?>><?php echo $monthName; ?></option>
<?php } ?>
   </select>

   <h4 class="AverageSans">Minimum Amount</h4>
   <input type="number" step="0.01" min="0" name="amount" value="<?php echo $fAmount; ?>" />

   <h4 class="AverageSans">Category</h4>
   <select name="category" class="gap-bottom">
      <option value="" <?php selected($fCategory, ""); ?>>Show all</option>
<?php
$query = "SELECT catId, category FROM Categories";
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $id = $row['catId'];
   $name = $row['category'];
?>
      <option value="<?php echo $id; ?>" <?php selected($fCategory, $id); ?>><?php echo $name; ?></option>
<?php } ?>
   </select>

   <h4 class="AverageSans">Description</h4>
   <input type="search" name="description" value="<?php echo $fDesc; ?>" />

   <div class="gap-top align-center">
   <input type="submit" name="filter" value="Filter" />
   <input type="submit" name="reset" value="Reset" class="asphalt" />
   </div>

</form>

</div>

<?php } ?>