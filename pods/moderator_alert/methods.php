<?


	function moderatorAlertSetup() { 
	
		return array('moderator_alert_email'=>'Send moderator alert email to:');
	
	}
	
	
	function moderatorAlert($content,$subject=null) { 
		$POD = $content->POD;
		
		if (!$subject) {
			$subject = 'New content on ' . $POD->siteName(false);
		}
		
		if ($POD->libOptions('moderator_alert_email')) { 
			
			$POD->startBuffer();
			$content->output('moderator_alert',dirname(__FILE__));
			$email = $POD->endBuffer();
			$headers = "From: " . $POD->libOptions('fromAddress') . "\r\n" . "X-Mailer: PeoplePods - XOXCO.com";
			mail($POD->libOptions('moderator_alert_email'),$subject,$email,$headers);	
		} else {	
			$POD->throwError('Cannot send moderator alert email until recipient email is set!');
		}
	
		
	}

	Content::registerMethod('moderatorAlert');