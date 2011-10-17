<?

	$path = dirname(__FILE__);

	$POD->registerPOD(
		'contact_form',
		'Contact form functions and handler',
		array('^contact'=>'contact_form/handler.php'),
		array(),
		"$path/methods.php",
		'contact_form_setup'
	);

?>