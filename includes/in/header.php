<?php if ($include) { ?>

<!doctype html>
<html>

<head>

<title>First National Bank of <?php getName($_SESSION['this_id']); ?></title>

<link type="text/css" rel="stylesheet" href="css/groundwork.css">
<script src="js/libs/jquery-1.9.1.min.js"></script>
<!--[if IE]><link type="text/css" rel="stylesheet" href="css/groundwork-ie.css"><![endif]-->
<!--[if lt IE 9]><script type="text/javascript" src="js/libs/html5shiv.min.js"></script><![endif]-->
<!--[if IE 7]><link type="text/css" rel="stylesheet" href="css/font-awesome-ie7.min.css"><![endif]-->
</head>

<body>

<div class="row">

<div class="four sixths centered">
<h1 class="AverageSans align-center gapped responsive">
   <a href="index.php">First National Bank of <?php getName($_SESSION['this_id']); ?></a>
</h1>

<nav>
<ul class="row">
   <li class="one fifth"><a href="index.php">Home</a></li>
   <li class="menu one fifth">
      <a href="#">Transactions</a>
      <ul>
         <li><a href="#">Deposit</a></li>
         <li><a href="#">Withdraw</a></li>
         <li><a href="#">Transfer</a></li>
         <li><a href="#">Transaction History</a></li>
      </ul>
   </li>
   <li class="menu one fifth">
      <a href="#">Accounts</a>
      <ul>
         <li><a href="#">Add Account</a></li>
         <li><a href="#">Modify Account</a></li>
         <li><a href="#">Delete Account</a></li>
      </ul>
   </li>
   <li class="one fifth"><a href="?p=settings">User Settings</a></li>
   <li class="one fifth"><a href="?p=logout">Logout</a></li>
</ul>
</nav>

<? } ?>