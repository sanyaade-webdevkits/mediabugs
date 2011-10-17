<? 


	$path = dirname(__FILE__);
	$POD->registerPOD('facebook_connect','Allows Login via facebook',
		array('^facebook$'=>'facebook_connect/index.php',
		'^facebook/friends'=>'facebook_connect/friends.php',
		'^xd_receiver.htm'=>'facebook_connect/xd_receiver.htm'),
		array(),
		$path . '/methods.php',
		'facebook_connect_settings'
		);

?>