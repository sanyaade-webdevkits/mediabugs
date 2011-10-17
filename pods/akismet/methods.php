<?

	function notSpam($doc) { 
	
		$doc->type = $doc->spam_type;
		$doc->spam_type = null;
		$doc->save();
	}

	function isSpam($doc) { 
	
		$POD = $doc->POD;
		
		require_once('akismet/Akismet.class.php');

		$akismet = new Akismet($POD->siteRoot(false) ,$POD->libOptions('akismet_key'));
		
		if ($doc->author()->id != $POD->anonymousAccount()) {
			$akismet->setCommentAuthor($doc->author()->nick);
			$akismet->setCommentAuthorEmail($doc->author()->email);
		}		


//		$akismet->setCommentAuthorURL($doc->link);
		$akismet->setCommentContent($doc->body);
			
		if ($akismet->isCommentSpam()) {
	
			if ($POD->libOptions('akismet_notify_email')) { 
				
				$message = 'Some suspicious content was just submitted to your site.  It was flagged as spam!
				
IP: ' . $_SERVER["REMOTE_ADDR"] .'				
				
Title:
' . $doc->headline . '
				
Body:
' . $doc->body;
			
				mail($POD->libOptions('akismet_notify_email'),'Questionable content detected',$message);
			
			}
		
			return true;
		} else {
			return false;	
		}
	
	}
	
	
	function akismet_settings() { 
	
		return array(
			'akismet_key'=>'Akismet API Key',
			'akismet_notify_email'=>'Specify notification email for spam (optional)'
		);
	}
	
	
	Content::registerMethod('isSpam');
	Content::registerMethod('notSpam');