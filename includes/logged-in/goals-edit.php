<?php if ($include) {

$id = clean($_GET['id']);
 
if (isset($_POST['edit'])) {
   $error = 0;

   $attArray = array("accId", "amount", "startDate", "endDate");
   $typeArray = array("int", "transaction", "date", "date");
   $update = "UPDATE Goals ";
   $where = " WHERE goalId = " . $id;
   
   for ($i = 0; $i < count($attArray); $i++) {
      $set = constructSet($attArray[$i], $typeArray[$i]);
      $query = $update . $set . $where;
      mysql_query($query);
      $error += mysql_errno();
   }

   if (isset($_POST['default'])) {
      $update = "UPDATE Accounts SET goalId = " . $id . " WHERE accId = " . clean($_POST['accId']);
      mysql_query($update);
      $error += mysql_errno();
   }
   else {
      if ($default == $id) {
         $update = "UPDATE Accounts SET goalId = NULL WHERE accId = " . clean($_POST['accId']);
         mysql_query($update);
         $error += mysql_errno();
      }
   }

   if ($error == 0)
      notifyMsg("Goal updated.");
   else
      errorMsg("Goal unsuccessfully updated.");
}
else if (isset($_POST['cancel'])) {
   header('location:?p=goals');
}

$checkQuery = sprintf("SELECT g.goalId FROM Accounts a, Goals g WHERE userId = %d AND g.accId = a.accId AND g.goalId = %d",
 $_SESSION['this_id'], $id);
$result = mysql_query($checkQuery);

$row = mysql_fetch_array($result);

if ($row['goalId'] != $id) {
   errorMsg("Bad request");
}
else {
   $query = "SELECT accId, amount, startDate, endDate FROM Goals g WHERE goalId = " . $id;
   $result = mysql_query($query);
   $row = mysql_fetch_array($result);

   $accId = $row['accId'];
   $amount = $row['amount'];
   $startDate = $row['startDate'];
   $endDate = $row['endDate'];
   $default = getDefaultGoal($accId);
}

?>

<h2 class="AverageSans">Edit a Goal</h2>

<form method="post">

   <div class="row">
      <div class="one half centered padded">
         <label for="accId">Account</label>
         <select name="accId" id="accId">
            <?php
            $query = "SELECT accId, accName FROM Accounts WHERE userId = " . $_SESSION['this_id'];
            $result = mysql_query($query);

            while ($row = mysql_fetch_array($result)) {
               $aId = $row['accId'];
               $name = $row['accName'];
            ?>
            <option value="<?php echo $aId; ?>" <?php selected($accId, $aId); ?>><?php echo $name; ?></option>
            <?php } ?>
         </select>
      </div>
   </div>
    
   <div class="row">
      <div class="one half centered padded">
         <label for="amount">Amount</label>
         <span class="prefix one sixth">$</span><input type="number" step="0.01" min="0" name="amount" id="amount" value="<?php echo $amount / 100; ?>" class="five sixths" />
      </div>
   </div>
    
   <div class="row">
      <div class="one half centered pad-top">
         <div class="one half padded">
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate" value="<?php echo $startDate; ?>" />
         </div>

         <div class="one half padded">
            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate" value="<?php echo $endDate; ?>" />
         </div>
      </div>
   </div>

   <div class="align-center padded">
         <input type="checkbox" name="default" id="default" value="default" <?php checked($id, $default); ?> />
          <label for="default" class="inline">Set as primary goal</label>
   </div>

   <div class="align-center gapped">
      <input type="submit" name="edit" value="Edit Goal" />
      <input type="submit" name="cancel" value="Cancel" class="asphalt" />
   </div>

</form>

<?php } ?>