<?php if ($include) {

$fAccount = clean($_GET['acc']);
$fMonth = clean($_GET['month']);
$fYear = clean($_GET['year']);
$fAmount = clean($_GET['amt']);
$fCategory = clean($_GET['cat']);
$fType = clean($_GET['type']);
$fDesc = clean($_GET['desc']);

if (isset($_POST['filter'])) {
   $fAccount = clean($_POST['accId']);
   $fMonth = clean($_POST['month']);
   $fYear = clean($_POST['year']);
   $fAmount = clean($_POST['amount']);
   $fCategory = clean($_POST['category']);
   $fType = clean($_POST['transaction']);
   $fDesc = clean($_POST['description']);

   if ($fAccount)
      $url = $url . "&acc=" . $fAccount;
   if ($fMonth)
      $url = $url . "&month=" . $fMonth;
   if ($fYear)
      $url = $url . "&year=" . $fYear;
   if ($fAmount)
      $url = $url . "&amt=" . $fAmount;
   if ($fCategory)
      $url = $url . "&cat=" . $fCategory;
   if ($fType)
      $url = $url . "&type=" . $fType;
   if ($fDesc)
      $url = $url . "&desc=" . $fDesc;

   header('location:?p=transactions' . $url);
}
else if (isset($_POST['delete'])) {
   $query = constructDelete();
   mysql_query($query);

   if (mysql_errno == 0)
      notifyMsg("Selected transactions deleted.");
   else
      errorMsg("Something bad happened...");
}
else if (isset($_POST['reset'])) {
   header('location:?p=transactions');
}

?>

<h2 class="AverageSans"><?php echo $fAccount ? getAccount($fAccount) . "\n" : "Recent Transactions\n"; ?></h2>

<div class="four fifths">

<form method="post">

<table>
   <thead style="font-size: 12px;">
      <tr>
         <th></th>
         <th>Date</th>
         <th>Transaction</th>
         <th>Amount</th>
         <th>Category</th>
         <th></th>
      </tr>
   </thead>
   <tbody>
<?php

$total = 0;

$select = "SELECT transId, payDate, paidToFrom, amount, c.category, description FROM Transactions t, Categories c";
$where = constructWhere() . " AND t.category = c.catId";
$order = constructOrder();
$limit = " LIMIT 30";
$query = $select . $where . $order . $limit;

$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $id = $row['transId'];
   $date = $row['payDate'];
   $for = $row['paidToFrom'];
   $amount = $row['amount'];
   $category = $row['category'];
   $description = $row['description'];

   $total = $total + $amount;
?>
      <tr class="tooltip" title="<?php echo $description; ?>">
         <td><input type="checkbox" name="transId[]" value="<?php echo $id; ?>" /></td>
         <td><?php echo $date; ?></td>
         <td><?php echo $for; ?></td>
         <td><?php echo amtToStrColor($amount); ?></td>
         <td><?php echo $category; ?></td>
         <td><a href="?p=transactions&amp;id=<?php echo $id; ?>"><i class="icon-edit"></i></a> </td>
      </tr>
<?php } ?>

      <tr style="font-weight: bold;">
         <td></td>
         <td></td>
         <td></td>
         <td><?php echo amtToStrColor($total); ?></td>
         <td></td>
         <td></td>
      </tr>
   </tbody>
</table>

<?php if (mysql_num_rows($result) == 0) { ?>
<p class="padded">No transactions to show.</p>
<?php } ?>

<input type="submit" name="delete" value="Delete Selected" class="gap-top" />

</form>

</div>

<?php } ?>