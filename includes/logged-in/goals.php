<?php if ($include) { ?>

<?php

function constructDelete() {
   $delete = "DELETE FROM Goals WHERE 0 ";
   $goalId = $_POST["goalId"];

   for ($i = 0; $i < count($goalId); $i++) {
      $delete = $delete . " OR goalId = " . clean($goalId[$i]);
   }

   return $delete;
}

$action = $_GET['a'];

if ($action == 'new') {
   include($in_path . 'goals-new.php');
}
elseif ($action == 'edit') {
   include($in_path . 'goals-edit.php');
}
else if (isset($_GET['id'])) {

}
else {
   include($in_path . 'goals-view.php');
   // require_once($in_path . 'goals-menu.php');
}

?>

<?php } ?>