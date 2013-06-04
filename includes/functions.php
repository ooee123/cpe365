<?php

function getName($id) {
   $query = sprintf("SELECT firstName, lastName, nickName FROM Users WHERE id = '%d'",
    $id);
   $result = mysql_query($query);

   while ($row = mysql_fetch_array($result)) {
      $firstName = $row['firstName'];
      $lastName = $row['lastName'];
      $nickName = $row['nickName'];
   }

   if ($nickName) {
      echo $nickName;
   }
   else {
      echo $firstName . ' ' . $lastName;
   }
}

function clean($str) {
   return mysql_real_escape_string(stripslashes($str));
}

?>