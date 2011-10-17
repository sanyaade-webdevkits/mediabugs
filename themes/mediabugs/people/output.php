<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/output.php
* Default output template for a person object. 
* Defines what a user profile looks like
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>

	<div class="column_8">
		<div id="profile">	

			<? if ($img = $user->files()->contains('file_name','img')) { ?>
				<img src="<?= $img->src(150,true); ?>" border="0" />
			<? } ?>	

			<h1><? $user->write('nick'); ?></h1>
		
			<? if ($user->get('aboutme')) { ?>
				<?= $user->aboutme; ?>
			<? } ?>
			<? if ($user->get('homepage')) { ?>
				<p><b><? $user->write('nick'); ?>'s Website:</b> <a href="<? $user->write('homepage'); ?>"><? $user->write('homepage'); ?></a></p>
			<? } ?>
			
			<? if ($POD->isAuthenticated()) { ?>
				<p><?= $POD->toggleBot($user->hasFlag('report',$POD->currentUser()),'toggleflag','Flagged','Flag a problem','method=toggleUserFlag&flag=report&user=' . $user->id); ?></p>
			<? } ?>

			<? if ($POD->isAuthenticated() && $POD->currentUser()->id==$user->id) { ?>
				<p><a href="<? $POD->siteRoot(); ?>/editprofile" class="littlebutton">Edit Profile</a></p>
			<? } ?>


			<div class="clearer"></div>
		</div>
		<? 	
			$offset = 0;
			if (isset($_GET['offset'])) {
				$offset = $_GET['offset'];
			}
			$docs = $user->POD->getContents(array('type'=>'bug','userId'=>$user->get('id')),null,20,$offset); 
			$title = $user->nick . " has reported " . $POD->pluralize($docs->totalCount(),'@number bug','@number bugs');
			$docs->output('short','header','pager',$title,$user->get('nick') . " hasn't posted anything yet.");
		?>	
	
	
	</div>
	<div class="column_4 last">	
	
		<? $POD->output('sidebars/recent_bugs'); ?>
		<? $POD->output('sidebars/browse'); ?>
		<? $POD->output('sidebars/member_leaderboard'); ?>

	</div>