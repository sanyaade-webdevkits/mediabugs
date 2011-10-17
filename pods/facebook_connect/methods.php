<? 



function facebook_connect_settings() { 
	return array(
		'fb_api_key'=>'Facebook API Key',
		'fb_api_secret'=>'Facebook API Secret',
	
	);

}


function facebookFriends($user,$fb) { 
		
		$POD = $user->POD;
			
		$query = 'SELECT uid, has_added_app FROM user WHERE uid IN '.
			'(SELECT uid2 FROM friend WHERE uid1 = '. $fbuser .')';
	
		try {
			$rows = $fb->api_client->fql_query($query);
		}
		catch (Exception $e) {
			$msg = $e->getMessage();
		}
		
		if ($rows) {
			foreach ($rows as $row) {
				if ($row['has_added_app']) {
				  $fbid[] = $row['uid'];
				}
			}
		
			if ($fbid) {    
				$people = $POD->getPeople(array('fbuid'=>$fbid));
			}
		}	
	    
	    
    	  if (!$people) {
      		$people = $POD->getPeople();
      	}

		return $people;


}


Person::registerMethod('facebookFriends');




?>