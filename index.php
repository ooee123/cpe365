<?php

$page = $_GET['p'];
$include = true;

$path = 'includes/';
$ext = '.php';

$out_path = 'includes/logged-out/';
$in_path = 'includes/logged-in/';

require_once($path . 'db-connect.php');
require_once($path . 'functions.php');

session_start();

if (!isset($_SESSION['this_id'])) {
   require_once($out_path . 'header.php');

   $file = $out_path . $page . $ext;

   if (file_exists($file))
      include($file);
   else
      include($out_path . 'login.php');

   require_once($out_path . 'footer.php');
}
else {
   require_once($in_path . 'header.php');

   $file = $in_path . $page . $ext;

   if (file_exists($file))
      include($file);
   else
      include($in_path . 'summary.php');

   require_once($in_path . 'footer.php');
}

require_once($path . 'db-close.php');

?>