<?php if ($include) { ?>

<h2 class="AverageSans">Edit an Account</h2>

<?php

if (isset($_POST['select'])) {
   header('location:?p=accounts&id=' . clean($_POST['accId']));
}

if (isset($_POST['delete'])) {
   $queries = deleteAccount(clean($_POST['accId']));
   $errors = 0;

   for ($i = 0; $i < count($queries); $i++) {
      mysql_query($queries[$i]);
      $errors += mysql_errno();
   }

   if ($errors == 0)
      notifyMsg("Account deleted.");
   else
      errorMsg("Deletion unsuccessful.");
}

if (isset($_GET['id'])) {
   $id = clean($_GET['id']);

   $query = sprintf("SELECT accType, accName FROM Accounts WHERE accId = %d AND userId = %d",
    $id, $_SESSION['this_id']);
   $result = mysql_query($query);

   if (mysql_num_rows($result) == 0)
      errorMsg("Invalid account id.");
   else {
      $row = mysql_fetch_array($result);

      $this_name = $row['accName'];
      $this_type = $row['accType'];
   }

   if (isset($_POST['edit'])) {
      $error = false;

      $this_name = clean($_POST['accName']);
      $this_type = clean($_POST['accType']);

      if (!$this_name || !$this_type) {
         $error = errorMsg("Please fill out all of the required fields.");
      }

      if (!$error) {
         $update = "UPDATE Accounts SET";
         $set = " accType = '" . $this_type . "'";
         $where = " WHERE accId = " . $id . " AND userId = " . $_SESSION['this_id'];
         mysql_query($update . $set . $where);

         $set = " accName = '" . $this_name . "'";
         mysql_query($update . $set . $where);

         if (mysql_errno() == 0)
            notifyMsg("Account updated.");
         else
            errorMsg("Something bad happened...");
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
         <input type="text" maxlength="32" name="accName" id="accName" value="<?php echo $this_name; ?>" placeholder="account name" />
      </div>
   </div>

   <div class="row">
      <div class="one half centered padded">
         <em>*Required fields</em>
      </div>
   </div>

   <div class="align-center padded">
      <input type="submit" name="edit" value="Edit Account" class="align-center" />
   </div>

</form>

<?php } else { ?>

<form method="post">

   <div class="row">
      <div class="one half centered padded">
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
   </div>

   <div class="align-center">
      <input type="submit" name="select" value="Edit Account" />
      <input type="submit" name="delete" value="Delete Account" class="asphalt" />
   </div>

</form>

<?php } ?>

<?php } ?>