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


	$interesting = $POD->interestingBugs(10,$offset);
	$POD->debug(2);
	$interesting->fill();
	$POD->debug(0);
	
/*
	$interesting = $POD->getContents(
		array('type'=>'bug'),
		'date DESC', 20);
*/

	// load bug types
	$bug_types = $POD->bugTypes();


	$welcome_message = $POD->getContent(array('stub'=>'welcome-message'));

	if (!$interesting->success()) { 
		$msg =  $interesting->error();
	}
?>


	<? if (isset($_GET['claimed'])) { 
		$bug = $POD->getContent(array('id'=>$_GET['claimed']));
		if ($bug->success()) { ?>
			<div class="dialog confirmation">
				<p><strong>You claimed a bug!</strong></p>
				<p>"<? $bug->permalink(); ?>" has been added to your <a href="/dashboard">My Bugs dashboard</a>.</p>
			</div>
		<? }
	} ?>

	<? if ($user->get('verificationKey')) { ?>
		<div class="dialog alert">
			
			<p><strong>Welcome to <? $POD->siteName(); ?>!</strong>  We are so glad you joined us.</p>
			<p>
				However, before you're allowed to post anything or leave comments, we need to <a href="<? $POD->siteRoot(); ?>/verify">verify your email address</a>.
				This lets us make sure that you aren't a spambot.
				You should already have the verification email in your inbox!
			</p>
			<p><a href="<? $POD->siteRoot(); ?>/verify">Verify My Account</a></p>
		
		</div>		
	<? } ?>		



	<div  id="welcome_block">
		<div class="welcome_box" id="welcome_explain">
			<? $welcome_message->output('interface_text'); ?>
			<div id="homepage_submit">
				<a href="<? $POD->siteRoot(); ?>/bugs/edit" class="button">Report a Bug Now</a>
				<div class="clearer"></div>
			</div>
		</div>	

		<div class="clearer"></div>	
	</div>
	
	
	<div class="column_8">
		<Br />
		<? if (isset($msg)) { ?>	
			<div class="info">
				<? echo $msg; ?>
			</div>
		<? } ?>
		
		
	
		<!-- this is where new posts from friends and groups show up -->
			<? 
					$interesting->output('featured_bug','header','footer','Recent Bugs','There\'s nothing new yet!'); 
			?>
				
	</div>
	<div class="column_4 last">
		<br />

		<? $POD->output('sidebars/recent_bugs'); ?>
		
		<? $POD->output('sidebars/browse'); ?>
	</div>

	<div class="clearer"></div>
