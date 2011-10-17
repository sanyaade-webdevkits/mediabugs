<?


	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>2));
	
	// login as admin
	$user = $POD->getPerson(array('nick'=>'admin'));
	$POD->changeActor(array('id'=>$user->id));
	
	
	// load each user
	// then see how many bugs and comments they have contributed over the last week

	$people = $POD->getPeople();

	$more = false;
	do {
	
		if($more) { 
			$people = $people->getNextPage();
		}


		foreach ($people as $person) { 

			$bugs = $POD->getContents(array('type'=>'bug','userId'=>$person->id,'date:gt'=>date('Y-m-d',strtotime('-30 days'))));
			$comments = $POD->getComments(array('userId'=>$person->id,'date:gt'=>date('Y-m-d',strtotime('-30 days'))));

			$rating = ($bugs->totalCount() * 3) + $comments->totalCount();

			// exclude admins from leaderboard
			if ($person->adminUser || $person->id==$POD->anonymousAccount()) {  
				$rating = null; 
			}

			if ($rating !== null) { 
				error_log("SETTING LEADERBOARD TO $rating FOR " . $person->nick . " = " . $person->id);
				$person->addMeta('leaderboard',$rating);
			} else {
				error_log("SETTING LEADERBOARD TO NULL FOR " . $person->nick . " = " . $person->id);
				$person->removeMeta('leaderboard');	
			}
			
		}

		$more = $people->hasNextPage();

	} while ($more);

	$POD->debug(2);
	error_log("PROCESSING OLD BUGS");
	
	// look for bugs that haven't been active in > 60 days
	// and are not yet closed.

	
	$OLD_BUGS = $POD->getContents(array('type'=>'bug','changeDate:lte'=>date('Y-m-d',strtotime('-60 days')),'bug_status:like'=>'open%'),null,100);
	$OLD_BUGS->fill();
	foreach ($OLD_BUGS as $bug) { 
		
		$bug->changeBugStatus('closed:unresolved');

		if ($bug->author()->id!=$POD->anonymousAccount()) { 
			$bug->author()->sendEmail('bug_closed',array('document'=>$bug));	
			echo "sending an email to " . $bug->author()->nick . " about bug " . $bug->headline . "<Br />";
		}
	
	}


?>