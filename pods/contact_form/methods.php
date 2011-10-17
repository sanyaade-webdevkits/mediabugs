<?

	function contact_form_setup() {
		return array('contact_form_to'=>'Email address for form');
	}

	// returns a contact form from $user
	function contactForm($POD,$from=null,$message=null,$error=null) {
		$POD->startBuffer();
		if ($POD->isAuthenticated()) { 
			$user = $POD->currentUser();
		} else {
			$user = $POD->getPerson();
		}
		if ($message) { 
			$user->set('contact_form_message',$message,false);  // temporary var
		} 
		if ($from) { 
			$user->set('email',$from,false);
		}
		if ($error) { 
			$user->set('contact_form_error',$error,false);
		}
		
		$user->output('contact_form.form',dirname(__FILE__));
		return $POD->endBuffer();
	}

	function contactFormFilter($POD,$text) { 

		$text = preg_replace("/\[contact_form\]/sm",$POD->contactForm(),$text);
		return $text;
	}
	
	function insertContactForm($doc,$field) { 

		$text = $doc->get($field);
		return $doc->POD->contactFormFilter($text);		
	
	}

	// sends an email with $message to the address specified in peoplepods settings from $from
	function sendContactEmail($POD,$from,$message) { 

		// sanitize $from
		if (filter_var($from, FILTER_VALIDATE_EMAIL)  == TRUE) {

			$headers = "From: {$from}\r\n" . "X-Mailer: PeoplePods - XOXCO.com";	
			if (mail($POD->libOptions('contact_form_to'),'Customer message from ' . $POD->siteName(false),$message,$headers)) {
				$POD->startBuffer();
				$POD->getPerson()->output('contact_form.success',dirname(__FILE__));
				return $POD->endBuffer();
			} else {
				return $POD->contactForm($from,$message,'Sorry, we were unable to send your message at this time.');
			}
		
		} else {
			return $POD->contactForm($from,$message,'Please use a valid email address so we can respond to your message.');
		}
	
	}

	PeoplePod::registerMethod('contactForm');
	PeoplePod::registerMethod('sendContactEmail');
	PeoplePod::registerMethod('contactFormFilter');
	Content::registerMethod('insertContactForm');

?>