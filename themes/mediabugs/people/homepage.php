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

	$interesting = $POD->interestingBugs(5,$offset);
	// load bug types
	$bug_types = $POD->bugTypes();


	// load welcome message
	if ($POD->isAuthenticated()) { 
		$welcome_message = $POD->getContent(array('stub'=>'welcome-message-loggedin'));	
	} else { 
		$welcome_message = $POD->getContent(array('stub'=>'welcome-message'));
	}

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
		<div class="welcome_box" id="features">
			<div class="column_padding">
				<? $POD->getContent(array('stub'=>'features_box'))->output('interface_text'); ?>
			</div>
		</div>
		<div class="welcome_box" id="welcome_explain">
			<div class="column_padding">
			<? $welcome_message->output('interface_text'); ?>
			<div id="homepage_submit">
				<a href="<? $POD->siteRoot(); ?>/bugs/edit" class="button">Report a Bug Now</a>
				<div class="clearer"></div>
			</div>
			</div>
		</div>	
		<div class="welcome_box" id="welcome_video">
			<div>
				<object width="280" height="157"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=13021709&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=13021709&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="280" height="157"></embed></object>
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
					$interesting->output('featured_bug','header','footer','Bugs to watch','There\'s nothing new yet!'); 
			?>
				
	</div>
	<div class="column_4 last">
		<br />

		<? $POD->output('sidebars/spread'); ?>
			
		<? $POD->output('sidebars/recent_bugs'); ?>
		
		<? $POD->output('sidebars/twitter'); ?>

		<? $POD->output('sidebars/browse'); ?>
	</div>

	<div class="clearer"></div>

	<div id="below_fold">	
		<div class="column_3">
			<? $POD->output('sidebars/member_leaderboard'); ?>
		</div>
		<div class="column_7 last">
			<? $POD->output('sidebars/recent_blogs'); ?>
		</div>
		<div class="clearer"></div>
	</div>