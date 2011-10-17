<?php

	include_once("../../lib/Core.php"); 

	
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'debug'=>0));
	if (!$POD->libOptions('enable_facebook_connect')) { 
		header("Location: " . $POD->siteRoot(false));
		exit;
	}

	

	$api_key = $POD->libOptions('fb_api_key');
	$secret = $POD->libOptions('fb_api_secret');


	include("facebook/facebook.php");
	$fb = new Facebook($api_key,$secret);
	$fbuser = $fb->get_loggedin_user();


		if (!$POD->isAuthenticated()) { 
	
			$user = $POD->getPerson();

			$user->set('facebook_api',$api_key,false);
			$user->set('facebook_secret',$secret,false);	

			if ($fbuser) { 
	
				// is there a person with this fbuser already in the db?  if so, log her in!
				$users = $POD->getPeople(array('fbuid'=>$fbuser));
				if ($users->count()==1) { 
					$user = $users->getNext();
					// if so, and the user is logged out, log him in!
					$days = 15;
					setcookie('pp_auth',$user->get('authSecret'),time()+(86400 * $days),"/");
					header("Location: /");				
					exit;
				}
		
				// set some vars

				try {
				$facebook_account = $fb->api_client->users_getInfo($fbuser, 'current_location,email,name,first_name,last_name'); 
				} catch (Exception $e) { };
				$facebook_account = $facebook_account[0];
				$user->set('nick',$facebook_account['name']);
	//			$user->set('email',$facebook_account['email']);

				$user->set('group',$_COOKIE['pp_group']);
				$user->set('invite',$_COOKIE['pp_invite_code']);
	//			$user->set('redirect','/facebook/friends');

				$user->set('fbuid',$fbuser);
				// set some variables 

				if ($_GET['group']) { 
					setcookie('pp_group',$_GET['group'],time()+60*60*24*30,"/");
				}
				$POD->header('Connect with Facebook');
				if ($msg) { ?>
					<div class="info">
						<? echo $msg; ?>		
					</div>
				<? }
				$user->output('join');
				$POD->footer();
				exit;
			
			}
		
		} else {

			if (!$POD->currentUser()->get('fbuid') && $fbuser) { 
				$test = $POD->getPeople(array('fbuid'=>$fbuser));
				if ($test->count()==0) {
					$POD->currentUser()->addMeta('fbuid',$fbuser);
					$msg= 'You have connected to Facebook!';				
				} else {
					$msg = "Another account is already connected to the Facebook account you chose.";
					$fbuser = null;
				}
			} 			
			if ($_GET['rfb']) { 
				$POD->currentUser()->addMeta('fbuid',null);
			}
			
			$POD->currentUser()->set('logged_into_facebook',isset($fbuser),false);
			$POD->currentUser()->set('facebook_api',$api_key,false);
			$POD->currentUser()->set('facebook_secret',$secret,false);
		
			$user = $POD->currentUser();	

		}
		
		
		if ($_GET['group']) { 
			setcookie('pp_group',$_GET['group'],time()+60*60*24*30,"/");
		}
		$POD->header('Connect with Facebook');
		if ($msg) { ?>
			<div class="info">
				<? echo $msg; ?>		
			</div>
		<? }		
		$user->output('login.facebook');
		
		$POD->footer();
