<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_authentication/login.php
* Handles requests to /login
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme
/**********************************************/

	include_once("../../lib/Core.php");
	
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));

	$redirect_after_login = false;
	$msg = null;
	$claimed = false;
	// repair damage to redirect url
	if ($_GET['checklogin']) { 
	
		$_GET['redirect'] .= "?";
		foreach ($_GET as $key=>$val) { 
			if ($key !='redirect' && $key!='checklogin') { 
				$_GET['redirect'] .= "{$key}={$val}&";
			}
		}
	}	
	
	if ($_GET['checklogin'] && $POD->isAuthenticated()) { 
		header("Location: " . $_GET['redirect']);
		exit;
	}
		
	if ($_POST) { 
		// if we have a form being submitted, handle the login
	 	if ($_POST['email'] && $_POST['password']) {
	 		$POD = new PeoplePod(array('authSecret'=>md5($_POST['email'].$_POST['password'])));
	 		if (!$POD->success())  {
				$msg = $POD->error();
	 		}		
			if (!$POD->isAuthenticated()) {
				$msg = "Oops!  We could not log you in using that email address and password.";
			} else {

					$days = 15;
					if ($_POST['remember_me']) { 
						$days = 100;
					}
					
					$USER = $POD->currentUser();

					if ($_COOKIE['claim']) { 
						$POD->changeActor(array('nick'=>'admin'));
						$claim = $POD->getContent(array('id'=>$_COOKIE['claim']));
						if ($claim->success()) { 
							if ($claim->author()->id == $POD->anonymousAccount()) { 
								
								$claim->set('userId',$USER->id);
								$claim->save();
								if($claim->success()) { 	
								
									$claimed = $claim->id;
										
									// we also need to reset the first status comment!
									$comments = $POD->getComments(array('contentId'=>$claim->id,'type'=>'status'),'date asc');
									while ($comment = $comments->getNext()) { 
										if ($comment->userId==$POD->anonymousAccount()) { 
											$comment->userId = $USER->id;
											$comment->save();
										}
									}
									
									
									$USER->addWatch($claim);
								} else {
								}
							} else {
							}
						}
						$POD->changeActor(array('id'=>$USER->id));
					}
		
					setcookie('claim','',time(),"/");
					setcookie('pp_auth',$POD->currentUser()->get('authSecret'),time()+(86400 * $days),"/");
					$redirect_after_login = true;
			}
		}

	}
	
	
	if ($redirect_after_login) {
		// if we logged in correctly, we redirect to the homepage of the site, or to any url passed in as a parameter	

		if ($_POST['redirect']) { 
			$redirect = $_POST['redirect'];
		} else if ($_GET['redirect']) {
			$redirect = $_GET['redirect'];
		} else {
			$redirect = $POD->siteRoot(false);
		}
		if (!($claimed===false)) { 
			$redirect .= "?claimed=" . $claimed;
		}

		header("Location: {$redirect}");	
	} else {
		$POD->header("Login");
		if ($msg) { ?>
			<div class="info">
				<?= $msg; ?>
			</div>	
		<? }
		
		$p = $POD->getPerson(); // create an empty person record 
		$p->set('redirect',$_GET['redirect'],false);
		$p->output('login');
		
		$POD->footer();
	} 
	
?>