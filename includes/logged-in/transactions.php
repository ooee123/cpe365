<?php if ($include) {

$action = $_GET['a'];

if ($action == 'new') {

}
else if ($action == 'edit') {

}
else if ($action == 'delete') {

}
else {
   require_once($in_path . 'menu-left.php');
   include($in_path . 'transactions-view.php');
   require_once($in_path . 'menu-right.php');
}

?>

<?php } ?>