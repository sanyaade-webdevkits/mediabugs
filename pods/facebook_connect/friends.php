<?php

	include_once("../../lib/Core.php"); 

	
	$POD = new PeoplePod(array('lockdown'=>'login','authSecret'=>$_COOKIE['pp_auth'],'debug'=>0));
	if (!$POD->libOptions('enable_facebook_connect')) { 
		header("Location: " . $POD->siteRoot(false));
		exit;
	}

	include("facebook/facebook.php");
	$api_key = $POD->libOptions('fb_api_key');
	$secret = $POD->libOptions('fb_api_secret');
	$fb = new Facebook($api_key,$secret);
	$fbuser = $fb->get_loggedin_user();

	
	if (!$fbuser) { 
		header("Location: /facebook");
		exit;
	} 
	
	$POD->header('Invite your Facebook Friends to share with you on NeighborGoods');
	if ($msg) { ?>
		<div class="info">
			<? echo $msg; ?>		
		</div>
	<? }
			
	$user = $POD->getPerson();
	$user->set('facebook_api',$api_key,false);
	$user->set('facebook_secret',$secret,false);	
	$user->output('facebook.friends');
	$POD->footer();
