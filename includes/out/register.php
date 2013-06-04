<? if ($include) {

if (isset($_POST['register'])) {
   // VALIDATE INPUT

   $this_user = clean($_POST['username']);
   $this_pass = clean($_POST['password']);
   $this_first = clean($_POST['firstname']);
   $this_last = clean($_POST['lastname']);
   $this_nick = clean($_POST['nickname']);

   $this_pass = md5($this_pass);

   // INSERT QUERY HERE
}

?>

<form method="post" action="?p=register" class="gapped triple">

   <div class="row padded">
      <input type="text" name="username" placeholder="username"
       class="two fifths centered" />
   </div>

   <div class="row align-center padded">
      <input type="password" name="password" placeholder="password"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="firstname" placeholder="first name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="lastname" placeholder="last name"
       class="two fifths centered" />
   </div>

   <div class="row padded">
      <input type="text" name="nickname" placeholder="optional: nickname"
       class="two fifths centered" />
   </div>

   <div class="align-center padded">
      <input type="submit" name="register" value="Register" />
   </div>
      
</form>

<div class="align-center padded">
   Already have an account? <a href="?p=login">Login now!</a>
</div>

<?php } ?>