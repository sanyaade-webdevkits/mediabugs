<?

	require_once("../../PeoplePods.php");
	
	$POD = new PeoplePod(array('debug'=>0,'lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
	
	
	$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
	$sortp = isset($_GET['sort']) ? $_GET['sort'] : null;
	
	switch ($sortp) {
	
		case null: $sort = 'date desc'; break;
		case 'name': $sort = 'headline asc'; break;
		case 'status': $sort = 'status desc'; break;
		case 'bugs': $sort = 'date desc'; break;
	}
	
	$POD->header('Media Outlets'); 

	$outlets = $POD->getContents(array('type'=>'bug_target'),$sort,25,$offset);
	
	$outlets->output('outlet.list','outlets.header','outlets.footer',null,null,"&sort=$sortp");	
	
	$POD->footer();
