<?php

$page = $_GET['p'];
$include = true;

$path = 'includes/';

$out_path = 'includes/out/';
$out_list = array(
   'login',
   'register',
   'verify'
);

$in_path = 'includes/in/';
$in_list = array(
   'summary',
   'settings',
   'logout'
);

require_once($path . 'db-connect.php');
require_once($path . 'functions.php');

session_start();

if (!session_is_registered(this_id)) {
   require_once($out_path . 'header.php');

   if (in_array($page, $out_list))
      include($out_path . $page . '.php');
   else
      include($out_path . 'login.php');

   require_once($out_path . 'footer.php');
}
else {
   require_once($in_path . 'header.php');

   if (in_array($page, $in_list))
      include($in_path . $page . '.php');
   else
      include($in_path . 'summary.php');

   require_once($in_path . 'footer.php');
}

require_once($path . 'db-close.php');

?>