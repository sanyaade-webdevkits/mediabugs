<?

	$POD->registerPOD(
		'bugs_outlets',
		'define the media outlet type, provide admin tools for moderation and adding information',
		array(
			'^outlets$'=>'bugs_outlets/handler.php',
			'^outlets/$'=>'bugs_outlets/handler.php',
			'^outlets/edit$'=>'bugs_outlets/edit.php',

		),
		array(),
		dirname(__FILE__) . "/methods.php"
	);



?>