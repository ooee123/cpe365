<?php if ($include) {

if (isset($_POST['save'])) {
   $error = false;

   $this_first = clean($_POST['firstName']);
   $this_last = clean($_POST['lastName']);
   $this_nick = clean($_POST['nickName']);

   if (!$this_first || !$this_last)
      $error = errorMsg("Please specify a first and last name.");

   if (!$error) {
      $attArray = array("firstName", "lastName", "nickName");
      $typeArray = array("string", "string", "string");
      $queries = constructUpdate("Users", $attArray, $typeArray);

      print_r(array_values($queries));

      for ($i = 0; $i < count($queries); $i++) {
         mysql_query($queries[$i]);
      }

      if (empty($_POST['nickName'])) {
         $query = "UPDATE Users SET nickName = NULL WHERE id = " . $_SESSION['this_id'];
         mysql_query($query);
      }

      notifyMsg("Settings updated.");
   }
}

$query = sprintf("SELECT userName, firstName, lastName, nickName FROM Users WHERE id = '%d'", $_SESSION['this_id']);
$result = mysql_query($query);

$row = mysql_fetch_array($result);

$username = $row['userName'];
$firstname = $row['firstName'];
$lastname = $row['lastName'];
$nickname = $row['nickName'];

?>

<h2 class="AverageSans">User Settings</h2>

<form method="post">

   <div class="row padded">
      <input type="text" name="userName" value="<?php echo $username; ?>"
       class="two fifths centered disabled" readonly="readonly" />
   </div>

   <div class="row align-center padded">
      <input type="password" name="password" placeholder="password"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" maxlength="32" name="firstName" value="<?php echo $firstname; ?>" placeholder="first name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" maxlength="32" name="lastName" value="<?php echo $lastname; ?>" placeholder="last name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" maxlength="32" name="nickName" value="<?php echo $nickname; ?>" placeholder="optional: nickname"
       class="two fifths centered" />
   </div>

   <div class="align-center padded">
      <input type="submit" name="save" value="Save Settings" />
   </div>
      
</form>

<?php } ?>