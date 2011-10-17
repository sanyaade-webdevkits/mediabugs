<? 
	$minutes = 15;
	$edit_minutes = intval(((strtotime($doc->date) + ($minutes*60)) - time())/60);

?>

<div id="new_bug_instructions">
	<h1>Thank you for reporting this bug!</h1>
	<div id="left">
	
		<ul id="next">		
			<li><a href="<?= $doc->permalink; ?>?msg=Bug+saved!" target="_new">View this bug report</a></li>
			<li>Link to your bug:<br/>
				<input value="<? $doc->write('permalink'); ?>" class="text" />
			</li>
			<li>Shortened link:<br/>
				<input value="<?= $doc->shortURL(); ?>" class="text"/>
			</li>
			<li><a href="<?= $doc->createTweet(); ?>" target="_new">Share this bug on Twitter</a></li>
			<li><a href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>" target="_new">Share this bug via email</a></li>
		</ul>
	</div>
	<div id="right">
		<? if ($POD->isAuthenticated()) { ?>
	
			<p><?= $POD->currentUser()->nick; ?>, we've filed this bug and attached it to your existing MediaBugs account.</p>
	
			<? if ($edit_minutes > 0) { ?>
				<p>You can <a href="<?= $doc->editlink; ?>" target="_new">edit this bug</a> for the next <?= $POD->pluralize($edit_minutes,'@number minute','@number minutes'); ?>.</p>
			<? } ?>
		
		<? } else { ?>
		
			<p>
				By filing this bug, you've opened up a communication channel with the reporters and media outlets involved.
			  	We believe it is important that you remain engaged in this discussion.  The best way to do that is to <strong><a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>" target="_new">create a MediaBugs account</a></strong>
			  	so that you can track this bug and make sure it gets closed. 
			</p>
		
			<p>
				<a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>" target="_new" class="littlebutton">Claim this bug</a>		
			</p>


		
		<? } ?>
	
	</div>	
	<div class="clearer"></div>
	
	
</div>
