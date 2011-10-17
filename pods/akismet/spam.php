<?

	require_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));
	
	
	$POD->header();
	
	$POD->getContent()->output('spam_caught',dirname(__FILE__));
		
	$POD->footer();
	