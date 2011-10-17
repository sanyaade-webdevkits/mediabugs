<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/output.php
* Default output template for a piece of content
* Use this file as a basis for your custom content templates
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/

$media_outlet = $POD->getContent(array('id'=>$doc->bug_target));

$subscribed = false;
if ($POD->isAuthenticated()) { 
	
	$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','parentId'=>$doc->id));
	$subscribed = ($subs->totalCount() > 0);
}
$minutes = 15;
$edit_minutes = intval(((strtotime($doc->date) + ($minutes*60)) - time())/60);



?>
<div class="column_8">

	<? if ($_GET['msg'] && $_GET['msg'] != 'Bug saved!') { ?>
	
		<div class="info">
			<?= strip_tags($_GET['msg']); ?>
		</div>

	<? } ?>
	<div id="bug_output">
	

			<div id="change_bug_status" style="display:none;">
				<? if ($_GET['msg'] == "Bug saved!") { ?>

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
					<hr />
				<? } ?>

				<h1>This bug is currently <strong><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></strong>.</h1>
				
				<div id="status_open" <? if (!preg_match("/open/",$doc->bug_status)) { ?>style="display:none;"<? } ?> >
				
					<div id="bug_statuses">
					<p><input type="radio" name="new_bug_status" id="bug_status_open" value="<?= $doc->bug_status; ?>" <? if (preg_match("/open/",$doc->bug_status)) { ?>checked<? } ?> /> <img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_20.png" id="bug_status_open_img" alt="<?= htmlspecialchars($doc->bug_status); ?>" align="absmiddle"  title="<?= htmlspecialchars($doc->bug_status); ?>" width="20" height="20" /> <span id="bug_status_open_label"><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></span></p>
					<? if ($POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
						<p><input type="radio" name="new_bug_status"  value="open"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" align="absmiddle"  alt="Open" border="0">&nbsp;Open</p>
						<p><input type="radio" name="new_bug_status"  value="open:under discussion"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_under_discussion_20.png" align="absmiddle"  alt="Open: Under Discussion" border="0">&nbsp;Open: Under Discussion</p>
						<p><input type="radio" name="new_bug_status"  value="open:responded to"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_responded_to_20.png" align="absmiddle"  alt="Open: Responded To" border="0">&nbsp;Open: Responded To</p>
						<p><input type="radio" name="new_bug_status"  value="closed:off topic"> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_off_topic_20.png" align="absmiddle"  alt="Closed: Off Topic" border="0">&nbsp;Closed: Off Topic</p>
						<p><input type="radio" name="new_bug_status"  value="closed:unresolved"> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_unresolved_20.png" align="absmiddle"  alt="Closed: Unresolved" border="0">&nbsp;Closed: Unresolved</p>
					<? } ?>
					<p><input type="radio" name="new_bug_status" value="closed:corrected" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_corrected_20.png" align="absmiddle"  alt="Closed: Corrected" border="0">&nbsp;Closed: Corrected</p>
					<p><input type="radio" name="new_bug_status" value="closed:withdrawn" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_withdrawn_20.png" align="absmiddle"  alt="Closed: Withdrawn" border="0">&nbsp;Closed: Withdrawn</p>
					<p><a href="/pages/status-explanation" target="_new">What do these mean?</a></p>	

					<? if ($POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
						<p><input type="checkbox" id="sendSurveyEmail" name="sendSurveyEmail" checked /> Send survey reminder email to <? $doc->author()->write('nick'); ?> if I close this bug?</p>
					<? } ?>
					
					</div>
					<div id="outlet_contacted">
					<p class="input">
						<label for="">Have you contacted this media outlet?</label>
						<input type="radio" name="meta_media_outlet_contacted" value="yes" id="contacted_yes" onchange="return chcontact();" <? if ($doc->media_outlet_contacted=="yes") {?>checked<? } ?>> Yes
						<input type="radio" name="meta_media_outlet_contacted" value="no" id="contacted_no" onchange="return chcontact();"<? if ($doc->media_outlet_contacted=="no" || !$doc->saved() ||  $doc->media_outlet_contacted=='') {?>checked<? } ?>> No
					</p>
					<p class="input" id="media_outlet_responded" <? if (!$doc->saved() || $doc->media_outlet_contacted=='no' || $doc->media_outlet_contacted=='') { ?>style="display:none;"<? }?>>
						<label for="">Has this media outlet responded?</label>
						<input type="radio" name="meta_media_outlet_responded" value="yes" id="responded_yes" onchange="return chresponded();" <? if ($doc->media_outlet_responded=="yes") {?>checked<? } ?>> Yes
						<input type="radio" name="meta_media_outlet_responded" value="no" id="responded_no" onchange="return chresponded();"<? if ($doc->media_outlet_responded=="no" || $doc->media_outlet_responded=='' || !$doc->saved()) {?>checked<? } ?>> No
					</p>
					
					<p class="input" id="media_response" <? if (!$doc->saved() || $doc->media_outlet_response=='') { ?>style="display:none;"<? }?>>
						<label for="">What was the media outlet's response?</label>
						<textarea id="meta_media_outlet_response" name="meta_media_outlet_response" class="text tinymce"><? $doc->htmlspecialwrite('media_outlet_response'); ?></textarea>
					</p>
					</div>
				</div>
				<div id="status_closed"  <? if (preg_match("/open/",$doc->bug_status)) { ?>style="display:none;"<? } ?> >
					<p><input type="radio" name="new_bug_status" id="bug_status_closed" value="<?= $doc->bug_status; ?>" <? if (!preg_match("/open/",$doc->bug_status)) { ?>checked<? } ?> /> <img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_20.png" id="bug_status_closed_img" alt="<?= htmlspecialchars($doc->bug_status); ?>" align="absmiddle"  title="<?= htmlspecialchars($doc->bug_status); ?>" width="20" height="20" /> <span id="bug_status_closed_label"><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></span></p>
					<p><input type="radio" name="new_bug_status" value="reopen" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" alt="Open" align="absmiddle"  title="open" width="20" height="20" /> Reopen this bug</p>
				</div>

				<div class="clearer"></div>
	
				<p><input type="button" value="Done" class="littlebutton" onclick="return changeBugStatus(<?= $doc->id; ?>);" /></p>
			
			</div>

			<div id="bug_info">
				<? if ($POD->isAuthenticated() && ($POD->currentUser()->id == $doc->userId)) { ?>
					<div id="bug_owner_message">
						You created this bug. <a href="#" onclick="return showStatusChange();" id="bug_status_link" title="You can update the bug status at any time by clicking here.">Update it when its status changes, or if you contact the publication.</a>
					</div>
				<? } ?>
				<? if ($POD->isAuthenticated() && ($POD->currentUser()->adminUser) && ($POD->currentUser()->id != $doc->userId)) { ?>
					<div id="bug_owner_message">
						As an admin, <a href="#" onclick="return showStatusChange();" id="bug_status_link" title="You can update the bug status at any time by clicking here.">you may edit this bug's status.</a>
					</div>
				<? } ?>

				<div class="bug_status">
					Bug #<?= $doc->id; ?>
					<img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_50.png" alt="<?= htmlspecialchars($doc->bug_status); ?>" title="<?= htmlspecialchars($doc->bug_status); ?>" width="50" height="50" id="bug_status_icon" />
				</div>
				<h1><?= $doc->bugHeadline(); ?></h1>
				<span class="byline">Reported by <? $doc->author()->permalink(); ?> on <strong><?= date('M j, Y',strtotime($doc->date)); ?></strong></span>
				<ul id="bug_actions">
					<? if ($POD->isAuthenticated()) { ?>
						<li><?= $POD->toggleBot($POD->currentUser()->isWatched($doc),'togglewatch','Stop tracking','Track','method=toggleWatch&content='.$doc->id,null,null,'Stop tracking this bug on your My Bugs dashboard','Track this bug on your My Bugs dashboard'); ?></li>
						<li><?= $POD->toggleBot($subscribed,'togglesub','Stop receiving updates','E-mail me updates','method=toggleSub&contentId='.$doc->id); ?></li>
					<? } else { ?>
						<li><a id="togglewatch" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Track</a></li>
						<li><a id="togglesub" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Email me updates</a></li>
					<? } ?>
					<li><a id="rsslink" href="<?= $doc->permalink ?>/feed">RSS</a></li>						
					<li><a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-via="media_bugs">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></li>
					<? if ($POD->isAuthenticated()) { ?>
						<li><a id="sendlink" href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>">Send</a></li>
						<li><?= $POD->toggleBot($doc->hasFlag('report',$POD->currentUser()),'toggleflag','Flagged','Flag a problem','method=toggleFlag&flag=report&content='.$doc->id); ?></li>
					<? } else { ?>
						<li><a id="toggleflag" href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>">Flag a problem</a></li>
					<? } ?>
				</ul>
				<div class="clearer"></div>
			</div>
			<div class="clearer"></div>			
			<div id="media_info">
				<? $media_outlet->output('outlet.widget'); ?>
				<div class="media_info_text">
					This bug appeared in a news report published by <strong><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $media_outlet->id; ?>"><?= $media_outlet->headline; ?></a></strong> on <strong><?= date('M j, Y',strtotime($doc->report_date)); ?></strong><? if ($doc->reporter) { ?> by <strong><?= $doc->reporter; ?></strong><? } ?>.
					<? if ($doc->link) { ?><strong><a href="<?= $doc->link; ?>">View the original news report</a></strong>.<? } ?>
				</div>
				<div class="clearer"></div>			
			</div>

			<div id="bug_body">
				<strong style="display:inline">Bug Type:&nbsp;</strong> <?= $doc->bug_type; ?>
				
				<? $doc->write('body'); ?>
		
				<? if ($doc->get('supporting_evidence')) {  ?>
					<h2>Supporting Information:</h2>
					<? $doc->write('supporting_evidence');
				} ?>
					
				<? if ($doc->files()->count() >0) { ?>
					<h2>Attached Files:</h2>
					<? foreach ($doc->files() as $file) { ?>
						<? if ($file->isImage()) { ?>
							<a href="<?= $file->original_file; ?>"><img src="<?= $file->thumbnail; ?>" border=0></a>				
						<? } else { ?>
							<a href="<?= $file->original_file; ?>"><img src="<? $POD->templateDir(); ?>/img/document_stroke_32x32.png" border="0" width="32" style="padding:9px;" /></a>
						<? } ?>
					<? } ?>
				<? } ?>
				
				<h2>Response</h2>
			
					<p id="media_outlet_contacted_yes" 	<? if ($doc->get('media_outlet_contacted')!='yes') { ?>style="display:none;"<? } ?>>
						<? $doc->author()->permalink(); ?> has contacted <?= $media_outlet->bugTargetBrowseLink(); ?>
						<span id="did_media_outlet_respond" <? if (!$doc->media_outlet_response) {?>style="display:none;"<? } ?>>and received the following response.</span>
					</p>
					<div id="media_outlet_response">
					<? if ($doc->media_outlet_response) { 
						$doc->write('media_outlet_response');
					} ?>
					</div>
	
					<p id="media_outlet_contacted_no" 	<? if ($doc->get('media_outlet_contacted')!='no') { ?>style="display:none;"<? } ?>><? $doc->author()->permalink(); ?> has not contacted <?= $media_outlet->bugTargetBrowseLink(); ?></p>
			</div>

			
			<h2>Bug History</h2>			

			<div id="bug_history">
				<? 
					$status = new Stack($POD,'comment');
					$comments = new Stack($POD,'comment');
					foreach ($doc->comments() as $comment) {
				   		if ($comment->type == 'status') { 
							$status->add($comment);
						} else {
							$comments->add($comment);
						}
					} 
					
					$status->output('bug.history');
				?>
			</div>
	</div>	

	<div id="comments">
		<h2>Discussion <? if ($POD->isAuthenticated()) {?><a href="#reply" class="with_right_float littlebutton">Leave a comment</a><? } else { ?><a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" class="with_right_float littlebutton">Leave a comment</a><? } ?></h2>
		<!-- COMMENTS -->	
		<? 
			$comments->output('comment');
		?>
		<!-- END COMMENTS -->
	</div>	
	<? if ($this->POD->isAuthenticated()) { ?>
		<div id="comment_form" <?  if (preg_match("/closed/",$doc->bug_status)) {?>style="display:none;"<? } ?>>
			<a name="reply"></a>
				<h3>Leave a comment</h3>
				<form method="post" id="add_comment" class="valid">
					<p style="margin:0px;" class="right_align">You are logged in as <? $POD->currentUser()->permalink(); ?>.  <a href="<? $POD->siteRoot(); ?>/logout">Logout</a></p>
					<p class="input"><textarea name="comment" class="text required" id="comment"></textarea></p>
					<div id="comment_extras">
						<p>Are you a direct participant in this story?</P>
						<p><input type="checkbox" name="journalist" id="journalist"> <label for="journalist">I am the journalist responsible.</label></p>
						<p><input type="checkbox" name="participant" id="participant"> <label for="participant">I am mentioned in this report or was interviewed for it.</label></p>
					</div>
					<p><input type="submit" value="Post Comment" class="button" /></p>
				</form>
			<div class="clearer"></div>		
		</div>
	<? } ?>	
</div>

<div class="column_4 last" id="post_info">


	<div class="sidebar">
	

		<? if ($POD->isAuthenticated() && $POD->currentUser()->id==$doc->author()->id) { ?>
			<p><strong>You reported this bug.</strong></p>
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number other person thinks this is a bug','@number other people think this is a bug'); ?></p>
		<? } else if ($POD->isAuthenticated()) { ?>
				<?= $POD->toggleBot($doc->hasFlag('metoo',$POD->currentUser()),'metoo','I think this is a bug too!','I think this is a bug too!','method=toggleFlag&flag=metoo&content=' . $doc->id,'metoocounter'); ?>		
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number person thinks this is a bug','@number people think this is a bug'); ?></p>
		<? }  else { ?>
			<p>
				<a href="<? $POD->siteRoot(); ?>/join?redirect=<?= $doc->permalink; ?>" id="metoo">I think this is a bug too!</a>
			</p>	
			<p id="metoo_counter"><?= $POD->pluralize($doc->flagCount('metoo'),'@number person thinks this is a bug','@number people think this is a bug'); ?></p>		
		<? } ?>

		<? if ($doc->isEditable()) { ?>
			<p><a href="<? $doc->write('editlink'); ?>" title="Edit this post" class="edit_button">Edit this bug</a></p>
			<? if ($POD->currentUser()->adminUser) { ?>
				<p><?= $POD->toggleBot($doc->hasFlag('featured'),'togglefeatured','Stop featuring this bug','Feature this bug','method=toggleFlag&type=global&flag=featured&content='.$doc->id); ?></p>
			<? } ?>
		<? } ?>

	</div>

	
	<? $POD->output('sidebars/recent_bugs'); ?>
	
	<? $POD->output('sidebars/browse'); ?>
	
</div>

<? if ($_GET['msg'] == "Bug saved!") { ?>
	<script type="text/javascript">
		showStatusChange();
	</script>
<? } ?>