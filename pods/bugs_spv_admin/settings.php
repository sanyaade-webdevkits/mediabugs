<?php

$POD->registerPOD(
	'bugs_spv_admin',
	'Admin tool for MediaBugs Single Publication Version',
	array(
		'^spvadmin$' => 'bugs_spv_admin/handler.php'
	),
	array(),
	dirname(__FILE__) . '/methods.php'
);