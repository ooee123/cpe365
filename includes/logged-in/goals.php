<?php if ($include) { ?>

<?php

$action = $_GET['a'];

if ($action == 'new') {
   include($in_path . 'goals-new.php');
}
else if (isset($_GET['id'])) {

}
else {
   include($in_path . 'goals-view.php');
}

?>

<?php } ?>