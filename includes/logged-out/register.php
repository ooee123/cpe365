<? if ($include) {

if (isset($_POST['register'])) {
   $error = false;

   $this_user = clean($_POST['userName']);
   $this_first = clean($_POST['firstName']);
   $this_last = clean($_POST['lastName']);
   $this_nick = clean($_POST['nickName']);
   $this_pass = $_POST['password'];

   if (!$this_user || !$this_first || !$this_last || !$this_pass)
      $error = errorMsg("Please fill out all of the required fields.");

   if (!validUserName($this_user))
      $error = errorMsg("Username is already taken.");

   if (!$error) {
      $attr = array("firstName", "lastName", "userName", "password", "nickName");
      $type = array("string", "string", "string", "password", "string");

      $query = constructInsert("Users", $attr, $type);
      mysql_query($query);

      $query = sprintf("SELECT id FROM Users WHERE userName = '%s'", $this_user);
      $result = mysql_query($query);

      if (mysql_num_rows($result) == 1) {
         $row = mysql_fetch_array($result);
         $_SESSION['this_id'] = $row['id'];

         header('location:?p=accounts&a=new');
      }
      else {
         $error = errorMsg("An unknown error has occurred.");
      }
   }
}

?>

<form method="post" class="gapped triple">

   <div class="row">
      <div class="one half padded">
         <label for="firstName">First Name*</label>
         <input type="text" maxlength="32" name="firstName" id="firstName" value="<?php echo $this_first; ?>" placeholder="first name" />
      </div>

      <div class="one half padded">
         <label for="lastName">Last Name*</label>
         <input type="text" maxlength="32" name="lastName" id="lastName" value="<?php echo $this_last; ?>" placeholder="last name" />
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label for="userName">Username*</label>
         <input type="text" maxlength="32" name="userName" id="userName" value="<?php echo $this_user; ?>" placeholder="username" />
      </div>

      <div class="one half padded">
         <label for="password">Password*</label>
         <input type="password" name="password" id="password" placeholder="password" />
      </div>
   </div>

   <div class="row">
      <div class="one half padded">
         <label for="nickName">Nickname (name to display on login)</label>
         <input type="text" maxlength="32" name="nickName" id="nickName" value="<?php echo $this_nick; ?>" placeholder="optional: nickname" />
      </div>

      <div class="one half padded">
         <em>*Required fields</em>
      </div>
   </div>

   <div class="align-center padded">
      <input type="submit" name="register" value="Register" />
   </div>
      
</form>

<div class="align-center padded">
   Already have an account? <a href="?p=login">Login now!</a>
</div>

<?php } ?>