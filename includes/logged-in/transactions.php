<?php if ($include) { ?>

<?php

function constructDelete() {
   $delete = "DELETE FROM Transactions WHERE 0 ";
   $transId = $_POST['transId'];

   for ($i = 0; $i < count($transId); $i++) {
      $delete = $delete . " OR transId = " . clean($transId[$i]);
   }

   return $delete;
}

$action = clean($_GET['a']);

if ($action == 'new') {
   include($in_path . 'transactions-new.php');
}
else if (isset($_GET['id'])) {
   include($in_path . 'transactions-edit.php');
}
else {
   include($in_path . 'transactions-view.php');
   require_once($in_path . 'transactions-menu.php');
}

?>

<?php } ?>