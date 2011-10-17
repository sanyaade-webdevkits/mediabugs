<?


	function mimeSend($user,$subject,$text,$html) {
	
		return $user->POD->mimeSendTo($user->email,$subject,$text,$html);
	}

	function mimeSendTo($POD,$to,$subject,$text,$html,$from=null)  {
	
		if (!isset($from)) { 
			$from = $POD->libOptions('fromAddress');
		}
		$user->success = false;
		$md5 = md5(time());
		$mime = "mimepart_$md5";

		$email =  "--$mime\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 7bit\r\n\r\n{$text}\r\n\r\n";
		$email .= "--$mime\nContent-Type: text/html; charset=utf-8\r\nContent-Transfer-Encoding: 7bit\r\n\r\n{$html}\r\n\r\n--$mime--";

		$headers = "From: {$from}\r\n" .
		"X-Mailer:  PeoplePods - XOXCO.com\r\n" .
		"Content-Type: multipart/alternative; boundary=\"$mime\"\r\n".
		"MIME-Version: 1.0\r\n";
		
		
		if (mail($to, $subject, $email, $headers)) {
			$user->success = true;
		} else {
			$user->throwError('Failed to send mime email to ' . $user->email);
		}

		return $user->success;
	
	}
	
	
	Person::registerMethod('mimeSend');
	PeoplePod::registerMethod('mimeSendTo');	
	
?>