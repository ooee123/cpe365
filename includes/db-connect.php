<?php

$host = 'csc-db0.csc.calpoly.edu';
$user = 'kly04';
$pass = 'password';
$db = 'test';

$connect = mysql_connect($host, $user, $pass) or die("Could not connect.");

mysql_select_db($db) or die("Could not select database.");

?>
