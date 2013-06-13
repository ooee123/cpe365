<?php if ($include) {

if (isset($_POST['new'])) {
   $error = 0;

   $startDate = $_POST['startDate'];
   $num = $_POST['num'];
   $period = $_POST['period'];
   $endDate = date_add(date_create($startDate), DateInterval::createFromDateString($num . " " . $period));

   $_POST['endDate'] = date_format($endDate, "Y-m-d");

   $attArray = array("accId", "amount", "startDate", "endDate");
   $typeArray = array("int", "transaction", "date", "date");
   $query = constructInsert("Goals", $attArray, $typeArray);
   mysql_query($query);
   $error += mysql_errno();

   if (isset($_POST['default'])) {
      $query = "SELECT MAX(goalId) AS maxGoal FROM Goals";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);
      $max = $row['maxGoal'];

      $update = "UPDATE Accounts SET goalId = " . $max . " WHERE accId = " . $_POST['accId'];
      mysql_query($update);
      $error += mysql_errno();
   }

   if ($error == 0)
      notifyMsg("Goal set. Good luck!");
   else
      errorMsg("Failed to set goal.");
}

?>

<h2 class="AverageSans">Set a Goal</h2>

<form method="post">

   <div class="row">
      <div class="one half centered padded">
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
   </div>

   <div class="row">
      <div class="one half centered padded">
         <label for="amount">Amount</label>
         <span class="prefix one sixth">$</span><input type="number" step="0.01" min="0" name="amount" id="amount" value="0" class="five sixths" />
      </div>
   </div>

   <div class="row">
      <div class="one half centered pad-top">
         <div class="one half padded">
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate" value="<?php echo date("Y-m-d"); ?>" />
         </div>
         <div class="one half padded">
            <label>Duration</label>
            <div class="one half pad-right">
               <select name="num">
               <?php for ($i = 1; $i <= 14; $i++) { ?>
                  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
               <?php } ?>
               </select>
            </div>
            <div class="one half pad-left">
               <select name="period">
               <?php
               $periods = array("Days", "Weeks", "Months", "Years");
               foreach ($periods as $period) { ?>
                  <option value="<?php echo $period; ?>"><?php echo $period; ?></option>
               <?php } ?>
               </select>
            </div>
         </div>
      </div>
      <div class="one fourth"></div>
   </div>

   <div class="align-center padded">
         <input type="checkbox" name="default" id="default" value="default" />
          <label for="default" class="inline">Set as primary goal</label>
   </div>

   <div class="align-center gapped">
      <input type="submit" name="new" value="Set Goal" />
   </div>

</form>

<?php } ?>