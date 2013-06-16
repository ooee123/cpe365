<?php if ($include) {

if (isset($_POST['login'])) {
   $this_user = clean($_POST['username']);
   $this_pass = clean($_POST['password']);

   $this_pass = md5($this_pass);

   $query = sprintf("SELECT * FROM Users WHERE userName = '%s' AND password = '%s'",
    $this_user, $this_pass);
   $result = mysql_query($query);

   if (mysql_num_rows($result) == 1) {
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $this_id = $row['id'];
      }
      $_SESSION['this_id'] = $this_id;
      header('location:?p=summary');
   }
   else {
?>

<p class="error message gapped triple">
   Wrong username or password.
</p>

<?php
   }
}
?>

<form method="post" action="?p=login" class="gapped triple">

   <div class="row padded">
      <input type="text" name="username" placeholder="username"
       class="two fifths centered" />
   </div>

   <div class="row align-center padded">
      <input type="password" name="password" placeholder="password"
       class="two fifths centered" />
   </div>

   <div class="align-center padded">
      <input type="submit" name="login" value="Login" />
   </div>
      
</form>

<div class="align-center padded">
   Don't have an account? <a href="?p=register">Register for free!</a>
</div>

<?php } ?>