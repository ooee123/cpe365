<?php if ($include) { ?>

<?php

$action = $_GET['a'];

if ($action == 'new') {
   include($in_path . 'accounts-new.php');
}
else if ($action == 'edit') {

}
else if ($action == 'delete') {

}
else {
   include($in_path . 'accounts-view.php');
}

?>

<?php } ?>