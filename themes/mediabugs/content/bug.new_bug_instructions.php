<? 
	$minutes = 15;
	$edit_minutes = intval(((strtotime($doc->date) + ($minutes*60)) - time())/60);
	$subscribed = false;
	if ($POD->isAuthenticated()) { 
		$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','parentId'=>$doc->id));
		$subscribed = ($subs->totalCount() > 0);
	}

?>


<div id="new_bug_instructions">
	<a href="#" onclick="$('#new_bug_instructions').fadeOut();return false;" id="closer">&nbsp;</a>
	<h1><img src="<? $POD->templateDir(); ?>/img/confirmation_hex.png" align="absmiddle" />&nbsp;Thank you for reporting this bug!</h1>
	
	<? if ($POD->isAuthenticated()) { ?>
	
		<p><input type="checkbox" id="subcheck" <? if ($subscribed) { ?>checked<? } ?> onclick="return toggleBot('subcheck','','','method=toggleSub&contentId='+<?= $doc->id; ?>,subCheckboxSuccess);" /> Send me a message when someone leaves a comment on this bug</p>
	
		<? if ($edit_minutes > 0) { ?>
			<p>You can <a href="<?= $doc->editlink; ?>">edit this bug</a> for the next <?= $POD->pluralize($edit_minutes,'@number minute','@number minutes'); ?>.</p>
		<? } ?>
	
	<? } else { ?>
	
		<p>
			By filing this bug, you've opened up a communication channel with the reporters and media outlets involved.
		  	We believe it is important that you remain engaged in this discussion.  The best way to do that is to <strong><a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>">create a MediaBugs account</a></strong>
		  	so that you can track this bug and make sure it gets closed. 
		</p>
	
		<p>
			<a href="<? $POD->siteRoot(); ?>/join?claim=<?= $doc->id; ?>" class="littlebutton">Claim this bug</a>		
		</p>
		<div class="clearer"></div>

	
	<? } ?>
	
	<p>You can link to your bug using the full url, below:</p>
	<p class="input"><input value="<? $doc->write('permalink'); ?>" class="text" /></p>
	
	<p>Or use the shortened url:</p>
	<p class="input"><input value="<?= $doc->shortURL(); ?>" class="text"/></p>
	
	<p>
	<a href="<?= $doc->createTweet(); ?>" class="littlebutton with_right_margin">Tweet this Bug</a>
	<a href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>" class="littlebutton">Email this Bug</a>
	</p>
	<div class="clearer"></div>
</div>