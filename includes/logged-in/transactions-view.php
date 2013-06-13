<?php if ($include) { ?>

<h2 class="AverageSans">Recent History</h2>

<table class="four fifths">
   <thead style="font-size: 12px;">
      <tr>
         <th></th>
         <th>Date</th>
         <th>Transaction</th>
         <th>Amount</th>
         <th>Category</th>
      </tr>
   </thead>
   <tbody>
   <form method="post" action="">

<?php

$select = "SELECT payDate, paidToFrom, amount, c.category FROM Transactions t, Categories c";
$where = constructWhere() . " AND t.category = c.catId";
$order = constructOrder();
$limit = " LIMIT 30";
$query = $select . $where . $order . $limit;

$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
   $date = $row['payDate'];
   $for = $row['paidToFrom'];
   $amount = amtToStrColor($row['amount']);
   $category = $row['category'];

?>

      <tr>
         <td><input type="checkbox" /></td>
         <td><?php echo $date; ?></td>
         <td><?php echo $for; ?></td>
         <td><?php echo $amount; ?></td>
         <td><?php echo $category; ?></td>
      </tr>

<?php } ?>

   </form>
   </tbody>
</table>

<?php } ?>