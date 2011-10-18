<?php
include_once("../../lib/Core.php"); 
$POD = new PeoplePod(array(
	'authSecret' => $_COOKIE['pp_auth'],
	'lockdown' => 'adminUser'
));

$POD->header('Admin Tool');

$POD->output('admintool');

$POD->footer();