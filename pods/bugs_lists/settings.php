<?

	$path = dirname(__FILE__);
	$POD->registerPOD(
		'bugs_lists',
		'defines commonly used lists of people and content',
		array(),
		array(
			'content_editlink_interface'=>'/peoplepods/admin/content/?id={this.id}',		
			'content_editpath_interface'=>'/peoplepods/admin/content/',		

		),
		$path . '/methods.php'	
	);

?>