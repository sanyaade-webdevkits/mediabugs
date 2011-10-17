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
		
			$subs = $POD->getContents(array('userId'=>$user->id,'type'=>'subscription','parentId:!='=>'null'));
			if ($user->updates || $subs->totalCount() > 0) {  
				$watchlist = new Stack($POD,'content');
					
				foreach ($subs as $sub) { 
					$watchlist->add($sub->parent());
				}
				
				if ($user->updates) { 
					$my_bugs = $POD->getContents(array('userId'=>$user->id,'type'=>'bug'));
					foreach ($my_bugs as $bug) {
						$watchlist->add($bug);
					}
				}
				echo "Dealing with {$user->nick}<br />";	
				
				$send = false;
				//  start generating the email
				ob_start();
				
				// iterate over each bug and see if there is new activity since last hour
				$watchlist->reset();
				foreach ($watchlist as $bug) { 
					$new_comments = $POD->getComments(array('contentId'=>$bug->id,'date:gt'=>date('Y-m-d H:i',strtotime("-1 hour"))));
					if ($new_comments->count() > 0) { 
						$send = true;
						$bug->output('subscriptions/bug');
					}
				}
				
				$email = ob_get_contents();
				ob_end_clean();
		
				if ($send) { 
					$user->sendEmail('subscription.hourly',array('message'=>$email));
					echo "Sending email to {$user->nick}<br />";
				}
				
			}
		}
		
		$first = false;		

		
	} while ($users->hasNextPage());





?>