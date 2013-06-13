<?php if ($include) { ?>

<h2 class="AverageSans">Add an Account</h2>

<?php

$this_type = "checking";

if (isset($_POST['new'])) {
   $error = false;

   $this_name = clean($_POST['accName']);
   $this_type = clean($_POST['accType']);

   if (!$this_name || !$this_type) {
      $error = errorMsg("Please fill out all of the required fields.");
   }

   if (strlen($this_name) > MAX_LEN) {
      $error = errorMsg("Account name must be less than 32 characters.");
   }

   if (!$error) {
      $attr = array("userId", "accName", "accType");
      $type = array("int", "string", "string");

      $query = constructInsert("Accounts", $attr, $type);

      mysql_query($query);
   }
}

?>

<form method="post">

   <input type="hidden" name="userId" value="<?php echo $_SESSION['this_id']; ?>" />

   <div class="row">
      <div class="one half centered padded">
         <label for="accType">Account Type*</label>
         <div class="row">
            <div class="one half">
               <input type="radio" name="accType" id="checking" value="checking" <?php echo checked("checking", $this_type); ?> />
               <label for="checking" class="inline">Checking</label>
            </div>
            <div class="one half">
               <input type="radio" name="accType" id="savings" value="savings" <?php echo checked("savings", $this_type); ?> />
               <label for="savings" class="inline">Savings</label>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="one half centered padded">
         <label for="accName">Account Name*</label>
         <input type="text" name="accName" id="accName" value="<?php echo $this_name; ?>" placeholder="account name" />
      </div>
   </div>

   <div class="row">
      <div class="one half centered padded">
         <em>*Required fields</em>
      </div>
   </div>

   <div class="align-center padded">
      <input type="submit" name="new" value="Add Account" class="align-center" />
   </div>

</form>

<?php } ?>