<?php if ($include) {

$id = clean($_GET['id']);

if (isset($_POST['edit'])) {
   $error = 0;

   $attArray = array("payDate", "accId", "paidToFrom", "amount", "description", "category");
   $typeArray = array("date", "int", "string", "transaction", "string", "category");
   $update = "UPDATE Transactions ";
   $where = " WHERE transId = " . $id;

   for ($i = 0; $i < count($attArray); $i++) {
      $set=constructSet($attArray[$i], $typeArray[$i]);
      $query = $update . $set . $where;
      mysql_query($query);
      $error += mysql_errno();
   }

   if ($error == 0)
      notifyMsg("Transaction updated.");
   else
      errorMsg("Transaction unsuccessfully updated.");
}
else if (isset($_POST['cancel'])) {
   header('location:?p=transactions');
}

$query = sprintf("SELECT transId FROM Accounts a, Transactions t WHERE userId = %d AND t.accId = a.accId AND transId = %d", $_SESSION['this_id'], $id);
$result = mysql_query($query);

$row = mysql_fetch_array($result);

if ($row['transId'] != $id) {
   errorMsg("Bad request.");
}
else {
   $query = "SELECT a.accId, payDate, paidToFrom, amount, description, category
   FROM Accounts a, Transactions t
   WHERE t.accId = a.accId AND t.transId = " . (isset($_GET['id']) ? $_GET['id'] : $_POST['transId']);
   $result = mysql_query($query);
    
   $row = mysql_fetch_array($result);

   $accId = $row['accId'];
   $payDate = $row['payDate'];
   $toFrom = $row['paidToFrom'];
   $amount = $row['amount'];
   $description = $row['description'];
   $category = $row['category'];
   $amount = sprintf("%.2f", abs($row['amount']) / 100);
   $type = $row['amount'] > 0 ? "deposit" : "withdrawal";
}

?>

<h2 class="AverageSans">Edit a Transaction</h2>

<form method="post">

   <div class="row">
      <div class="one half padded">
         <label for="accId">Account</label>
         <select name="accId" id="accId">
      <?php
      $query = "SELECT accId, accName FROM Accounts WHERE userId = " . $_SESSION['this_id'];
      $result = mysql_query($query);

      while ($row = mysql_fetch_array($result)) {
         $id = $row['accId'];
         $name = $row['accName'];
      ?>
            <option value="<?php echo $id; ?>" <?php selected($accId, $id); ?>><?php echo $name; ?></option>
      <?php } ?>
         </select>
      </div>
      <div class="one half padded">
         <label for="category">Category</label>
         <select name="category" id="category">
      <?php
      $query = "SELECT catId, category FROM Categories";
      $result = mysql_query($query);

      while ($row = mysql_fetch_array($result)) {
         $id = $row['catId'];
         $name = $row['category'];
      ?>
            <option value="<?php echo $id; ?>" <?php selected($category, $id); ?>><?php echo $name; ?></option>
      <?php } ?>
         </select>
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label>Transaction</label>
         <div class="row">
            <div class="one half">
               <input type="radio" name="transaction" value="deposit" id="deposit" <?php checked($type, "deposit"); ?> />
                <label for="deposit" class="inline">Deposit</label>
            </div>
            <div class="one half">
               <input type="radio" name="transaction" value="withdrawal" id="withdrawal" <?php checked($type, "withdrawal"); ?> />
                <label for="withdrawal" class="inline">Withdrawal</label>
            </div>
         </div>
      </div>
      <div class="one half padded">
         <label for="paidToFrom" class="inline">For</label>
         <input type="text" maxlength="32" name="paidToFrom" value="<?php echo $toFrom; ?>" />
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label for="payDate">Date</label>
         <input type="date" name="payDate" id="payDate" value="<?php echo $payDate; ?>" />
      </div>
      <div class="one half padded">
         <label for="amount">Amount</label>
         <span class="prefix one sixth">$</span><input type="number" step="0.01" min="0" name="amount" id="amount" value="<?php echo $amount; ?>" class="five sixths" />
      </div>
   </div>
 
   <div class="padded">
      <label for="description">Description (128 characters max)</label>
      <input type="text" maxlength="128" name="description" id="description" value="<?php echo $description; ?>" />
   </div>

   <div class="align-center">
      <input type="submit" name="edit" value="Save" />
      <input type="submit" name="cancel" value="Cancel" class="asphalt" />
   </div>

</form>

<?php } ?>