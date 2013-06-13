<?php if ($include) {

if (isset($_POST['new'])) {
   $attArray = array("accId", "payDate", "amount", "category", "paidToFrom", "description");
   $typeArray = array("int", "date", "transaction", "int", "string", "string");

   $query = constructInsert("Transactions", $attArray, $typeArray);

   mysql_query($query);

   if (mysql_errno() == 0)
      notifyMsg("Transaction added.");
   else
      errorMsg("Something bad happened...");
}

?>

<h2 class="AverageSans">Add a Transaction</h2>

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
            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
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
            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
      <?php } ?>
         </select>
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label>Transaction</label>
         <div class="row">
            <div class="one half">
               <input type="radio" name="transaction" value="deposit" id="deposit" checked="checked" />
                <label for="deposit" class="inline">Deposit</label>
            </div>
            <div class="one half">
               <input type="radio" name="transaction" value="withdrawal" id="withdrawal" />
                <label for="withdrawal" class="inline">Withdrawal</label>
            </div>
         </div>
      </div>
      <div class="one half padded">
         <label for="paidToFrom" class="inline">For</label>
         <input type="text" maxlength="32" name="paidToFrom" />
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label for="payDate">Date</label>
         <input type="date" name="payDate" id="payDate" value="<?php echo date("Y-m-d"); ?>" />
      </div>
      <div class="one half padded">
         <label for="amount">Amount</label>
         <span class="prefix one sixth">$</span><input type="number" step="0.01" min="0" name="amount" id="amount" value="0" class="five sixths" />
      </div>
   </div>
 
   <div class="padded">
      <label for="description">Description (128 characters max)</label>
      <input type="text" maxlength="128" name="description" id="description" />
   </div>

   <div class="align-center">
      <input type="submit" name="new" value="Add Transaction" class="align-center" />
   </div>

</form>

<?php } ?>