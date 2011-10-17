<? 

	include("../../PeoplePods.php");
	
	$POD = new PeoplePod(array('debug'=>2));
	$admin = $POD->getPerson(array('nick'=>'admin'));
	$POD->changeActor(array('id'=>$admin->id));
	
	$users = $POD->getPeople();
	$first = true;
	
	do {
	
		if (!$first) { 
			$users = $users->getNextPage();
		}
		

		foreach ($users as $user) { 
		
			$subs = $POD->getContents(array('userId'=>$user->id,'type'=>'subscription','parentId:='=>'null'));
			if ($subs->totalCount() > 0) {  
				echo "Dealing with {$user->nick}<br />";	
				
				$new_bugs = new Stack($POD,'content');
				
				foreach ($subs as $sub) { 
				
					$term = $sub->body;
					$mode = $sub->query_type;
					
					if ($mode=='status') { 
						$title = "By status: $term";
						$key = 'bug_status';
						
						if ($term == "open") { 
							$key = 'bug_status:like';
							$term = 'open%';
						}
						if ($term == "closed") { 
							$key = 'bug_status:like';
							$term = 'closed%';
						}			
					} else if ($mode=="outlet") { 
						$outlet = $POD->getContent(array('id'=>$term));
						$title = "By outlet: {$outlet->headline}";
						$key = 'bug_target';
					} else if ($mode=="type") { 
						$title = "By type: $term";
						$key = 'bug_type';
					} else if ($mode=="search") { 
						$title ="Search for \"{$term}\"";
						$key = 'or';
						$term = array('headline:like'=>"%{$term}%",'body:like'=>"%{$term}%");
						
						// also search the outlet database
						// if a matching outlet is found, display that as an alternative
						// Do you want to see bugs associated with "The Washington Post" instead?
						
					} else if ($mode=="date") { 
						$title ="By date";
						$key = 1;
						$term = 1;
					}
					
					$query = array(
						'type'=>'bug',
						$key=>$term,
					);
					
					if ($mode !='status') { 
						$query['bug_status:!='] = 'closed:off topic';
					}
					
					$query['date:gt'] = date('Y-m-d H:i',strtotime("-1 day"));
					
					$bugs = $POD->getContents($query,'date DESC',100);
					if ($bugs->count() > 0) {					
						$new_bugs->combineWith($bugs);
					}
				
				}
				
				
				if ($new_bugs->count() > 0) { 
					//  start generating the email
					ob_start();
					$bugs->sortBy('date');
					$new_bugs->output('subscriptions/new_bug',null,null);				
					
					$email = ob_get_contents();
					ob_end_clean();
			
					$user->sendEmail('subscription.daily',array('message'=>$email));
					echo "Sending email to {$user->nick}<br />";

				}
				
				
				
			}
		}
		
		$first = false;		

		
	} while ($users->hasNextPage());

?>