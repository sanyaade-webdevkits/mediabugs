<?
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));
	
	if (!$_GET['email']) {
		header("Location: /");
	} else {
		$user = $POD->getPerson(array('email'=>$_GET['email']));
		if ($user->success()) { 
			$user->removeMeta('newsletter');
			$POD->header('Unsubscribed from MediaBugs');
			$user->output('unsubscribed');
			$POD->footer();
		} else {
			header("Location: /");
		}
	
	}


?>