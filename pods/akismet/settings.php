<?
	
	
	$POD->registerPOD(
		'akismet',
		'creates a $doc->isBug() function that checks akismet',
		array('^spam'=>'akismet/spam.php'),
		array(),
		dirname(__FILE__).'/methods.php',
		'akismet_settings'	
	);



?>