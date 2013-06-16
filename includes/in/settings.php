<?php if ($include) { ?>

<h2 class="AverageSans gapped">User Settings</h2>

<form method="post" action="?p=settings">

   <div class="row padded">
      <input type="text" name="username" placeholder="username"
       class="two fifths centered disabled" readonly="readonly" />
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
      <input type="submit" name="register" value="Save Settings" />
   </div>
      
</form>

<?php } ?>