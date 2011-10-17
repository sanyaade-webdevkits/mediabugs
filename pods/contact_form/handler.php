<?
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));
	
	if ($_POST) { 	
		$html = $POD->sendContactEmail($_POST['from'],stripslashes($_POST['message']));
	} else {
		$html = $POD->contactForm();
	}
	
	$POD->header('Contact Us');
	echo $html;
	$POD->footer();
	
?>