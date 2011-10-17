<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/dashboard.php
* Used by the dashboard module to create homepage of the site for members
* Displays a list of content the current user has created,
* and content from the user's friends and groups
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>

<?	


	$offset = 0;	
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}

	$mode = $_GET['mode'] ? $_GET['mode'] : 'activity';	

	if ($mode=="activity") { 
		$docs = $POD->getContents(array('type'=>'bug','flag.name'=>'watching','flag.userId'=>$POD->currentUser()->get('id')),'commentDate desc',20,$offset);
	} else if ($mode=="edit") {
		$docs = $POD->getContents(array('type'=>'bug','flag.name'=>'watching','flag.userId'=>$POD->currentUser()->get('id')),'flag.date desc',20,$offset);	
	}
?>
	<div class="column_8">		
		<? if (isset($msg)) { ?>	
			<div class="info">
				<? echo $msg; ?>
			</div>
		<? } ?>
	
		<!-- this is where new posts from friends and groups show up -->
			<? 
					if ($mode=='activity') { 
						$header = "My Bugs <a href=\"?mode=edit\" class=\"small\">Edit</a>";
					} else {
						$header = "Manage My Bugs <a href=\"?mode=activity\" class=\"small\">View Activity</a>";					
					}
					$docs->output("dashboard.{$mode}",'header','pager',$header,'This is your personalized MediaBugs dashboard. This page will collect any
						bugs you submit, along with any interesting bugs you find around the site
						that you select. Just click "Track" on the bug\'s page.'); 
			?>

		
		
	</div>
	<div class="column_4 last">


		<? $POD->output('sidebars/recent_bugs'); ?>
		<? $POD->output('sidebars/browse'); ?>


	</div>
