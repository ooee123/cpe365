<?php if ($include) {

$action = clean($_GET['a']);

if ($action == 'new') {
   include($in_path . 'transactions-new.php');
}
else if (isset($_GET['id'])) {
   include($in_path . 'transactions-edit.php');
}
else {
   require_once($in_path . 'menu-left.php');
   include($in_path . 'transactions-view.php');
   require_once($in_path . 'menu-right.php');
}

?>

<?php } ?>