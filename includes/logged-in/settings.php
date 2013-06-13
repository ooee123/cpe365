<?php if ($include) {

$query = sprintf("SELECT userName, firstName, lastName, nickName FROM Users WHERE id = '%d'", $_SESSION['this_id']);
$result = mysql_query($query);

$row = mysql_fetch_row($result, MYSQL_ASSOC);

$username = $row['userName'];
$firstname = $row['firstName'];
$lastname = $row['lastName'];
$nickname = $row['nickName'];

?>

<h2 class="AverageSans">User Settings</h2>

<form method="post" action="?p=settings">

   <div class="row padded">
      <input type="text" name="username" value="<?php echo $username; ?>"
       class="two fifths centered disabled" readonly="readonly" />
   </div>

   <div class="row align-center padded">
      <input type="password" name="password" placeholder="password"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="first name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="last name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="nickname" value="<?php echo $nickname; ?>" placeholder="optional: nickname"
       class="two fifths centered" />
   </div>

   <div class="align-center padded">
      <input type="submit" name="register" value="Save Settings" />
   </div>
      
</form>

<?php } ?>