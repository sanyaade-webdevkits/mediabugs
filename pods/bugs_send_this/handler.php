<?

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'lockdown'=>'login'));

	if ($_GET['id']) { 
		
		$doc = $POD->getContent(array('id'=>$_GET['id']));
		if ($doc->success()) { 
			$POD->header('Send ' . $doc->bugHeadline());
			$doc->output('send_this.form',dirname(__FILE__));
			$POD->footer();
		} else {
			header("Location: " . $POD->siteRoot(false));
			exit;
		}	
	} else if ($_POST) { 
		
		$to = $_POST['email'];
		$message = stripslashes($_POST['message']);
		
		$doc = $POD->getContent(array('id'=>$_POST['id']));
		
		$doc->set('send_this_sender_email',$POD->currentUser()->email,false);		
		$doc->set('send_this_sender_name',$POD->currentUser()->nick,false);
		$doc->set('send_this_message',$message,false);
		$doc->set('send_this_recipient',$to,false);
			
		// sanitize $to
		if (filter_var($to, FILTER_VALIDATE_EMAIL)  == TRUE) {


						
			$POD->startBuffer();
			$doc->output('send_this.html',dirname(__FILE__));
			$html = $POD->endBuffer();

			$POD->startBuffer();
			$doc->output('send_this.text',dirname(__FILE__));
			$text = $POD->endBuffer();
			
			$subject = $POD->siteName(false) . ": " . $doc->bugHeadline();
		
			if ($POD->mimeSendTo($to,$subject,$text,$html)) { 
				$POD->header('Send ' . $doc->bugHeadline());
				$doc->output('send_this.success',dirname(__FILE__));
				$POD->footer();
			} else {
				$POD->header('Send ' . $doc->bugHeadline());
				$doc->set('send_this_error','Sorry, we were unable to send this message.',false);
				$doc->output('send_this.form',dirname(__FILE__));
				$POD->footer();				
			}
		
		} else {
			$POD->header('Send ' . $doc->bugHeadline());
			$doc->set('send_this_error','Please specify a valid email address!',false);
			$doc->output('send_this.form',dirname(__FILE__));
			$POD->footer();				
						
		}	

	} else {
		header("Location: " . $POD->siteRoot(false));
		exit;
	}





?>