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

$author_name = $doc->author()->nick;
if (!empty($doc->who_name) && !$doc->author_name->adminUser) {
	$author_name = $doc->who_name;
}
$fb_app_id = $POD->getContents(array('type'=>'admin_record'))->getNext()->fb_app_id;

?>
<div class="column_8">

	<? if ($_GET['msg'] && $_GET['msg'] != 'Bug saved!') { ?>
	
		<div class="info">
			<?= strip_tags($_GET['msg']); ?>
		</div>

	<? } ?>
	<? if ($POD->msg) { ?>
	
		<div class="info">
			<?= strip_tags($POD->msg); ?>
		</div>

	<? } ?>

	<div id="bug_output">
	
		<?php if ($POD->isAuthenticated() && $POD->currentUser()->adminUser): ?>
			<div id="change_bug_status" style="display:none;">
				<h1>This bug is currently <strong><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></strong>.</h1>
				
				<div id="status_open" <? if (!preg_match("/open/",$doc->bug_status)) { ?>style="display:none;"<? } ?> >
				
					<div id="bug_statuses">
					<p><input type="radio" name="new_bug_status" id="bug_status_open" value="<?= $doc->bug_status; ?>" <? if (preg_match("/open/",$doc->bug_status)) { ?>checked<? } ?> /> <img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_20.png" id="bug_status_open_img" alt="<?= htmlspecialchars($doc->bug_status); ?>" align="absmiddle"  title="<?= htmlspecialchars($doc->bug_status); ?>" width="20" height="20" /> <span id="bug_status_open_label"><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></span></p>
						<p><input type="radio" name="new_bug_status"  value="open"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" align="absmiddle"  alt="Open" border="0">&nbsp;Open</p>
						<p><input type="radio" name="new_bug_status"  value="open:under discussion"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_under_discussion_20.png" align="absmiddle"  alt="Open: Under Discussion" border="0">&nbsp;Open: Under Discussion</p>
						<p><input type="radio" name="new_bug_status"  value="open:responded to"> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_responded_to_20.png" align="absmiddle"  alt="Open: Responded To" border="0">&nbsp;Open: Responded To</p>
						<p><input type="radio" name="new_bug_status"  value="closed:off topic"> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_off_topic_20.png" align="absmiddle"  alt="Closed: Off Topic" border="0">&nbsp;Closed: Off Topic</p>
						<p><input type="radio" name="new_bug_status"  value="closed:unresolved"> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_unresolved_20.png" align="absmiddle"  alt="Closed: Unresolved" border="0">&nbsp;Closed: Unresolved</p>
					<p><input type="radio" name="new_bug_status" value="closed:corrected" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_corrected_20.png" align="absmiddle"  alt="Closed: Corrected" border="0">&nbsp;Closed: Corrected</p>
					<p><input type="radio" name="new_bug_status" value="closed:withdrawn" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/closed_withdrawn_20.png" align="absmiddle"  alt="Closed: Withdrawn" border="0">&nbsp;Closed: Withdrawn</p>
					<p><a href="/pages/status-explanation" target="_new">What do these mean?</a></p>	
					
					</div>
				</div>
				<div id="status_closed"  <? if (preg_match("/open/",$doc->bug_status)) { ?>style="display:none;"<? } ?> >
					<p><input type="radio" name="new_bug_status" id="bug_status_closed" value="<?= $doc->bug_status; ?>" <? if (!preg_match("/open/",$doc->bug_status)) { ?>checked<? } ?> /> <img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_20.png" id="bug_status_closed_img" alt="<?= htmlspecialchars($doc->bug_status); ?>" align="absmiddle"  title="<?= htmlspecialchars($doc->bug_status); ?>" width="20" height="20" /> <span id="bug_status_closed_label"><?= ucwords(preg_replace("/\:/",": ",$doc->bug_status)); ?></span></p>
					<p><input type="radio" name="new_bug_status" value="reopen" /> <img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" alt="Open" align="absmiddle"  title="open" width="20" height="20" /> Reopen this bug</p>
				</div>

				<div class="clearer"></div>
	
				<p><input type="button" value="Done" class="littlebutton" onclick="return changeBugStatus(<?= $doc->id; ?>);" /></p>
			
			</div>

		<?php endif ?>
			<div id="bug_info">
				<? if ($POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
					<div id="bug_owner_message">
						As an admin, <a href="#" onclick="return showStatusChange();" id="bug_status_link" title="You can update the bug status at any time by clicking here.">you may edit this bug's status.</a>
					</div>
				<? } ?>

				<div class="bug_status">
					Bug #<?= $doc->id; ?>
					<img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_50.png" alt="<?= htmlspecialchars($doc->bug_status); ?>" title="<?= htmlspecialchars($doc->bug_status); ?>" width="50" height="50" id="bug_status_icon" />
				</div>
				<h1><?= $doc->bugHeadline(); ?></h1>
				<span class="byline">Reported by <b><?php echo $author_name ?></b> on <strong><?= date('M j, Y',strtotime($doc->date)); ?></strong></span>
				<ul id="bug_actions">
					<? if ($POD->isAuthenticated()) { ?>
						<li><?= $POD->toggleBot($POD->currentUser()->isWatched($doc),'togglewatch','Stop tracking','Track','method=toggleWatch&content='.$doc->id,null,null,'Stop tracking this bug on your My Bugs dashboard','Track this bug on your My Bugs dashboard'); ?></li>
						<li><?= $POD->toggleBot($subscribed,'togglesub','Stop receiving updates','E-mail me updates','method=toggleSub&contentId='.$doc->id); ?></li>
					<? } ?>
					<li><a id="rsslink" href="<?= $doc->permalink ?>/feed">RSS</a></li>						
					<li><a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-via="media_bugs">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></li>
					<? if ($POD->isAuthenticated()) { ?>
						<li><a id="sendlink" href="<? $POD->siteRoot(); ?>/send?id=<?= $doc->id; ?>">Send</a></li>
					<? } ?>
					<?php if ($POD->currentUser()->adminUser): ?>
						<li><a href="<? $doc->write('editlink'); ?>">Edit</a></li>
					<?php endif ?>
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
	<div id="comment_form" <?  if (preg_match("/closed/",$doc->bug_status)) {?>style="display:none;"<? } ?>>
		<a name="reply"></a>
			<h3>Leave a comment</h3>
			<form method="post" id="add_comment" class="valid">
				<input type='hidden' name='meta_who_fb_id'>

				<p style="margin:0px;" class="right_align">
					<?php if ($POD->isAuthenticated()): ?>
						You are logged in as <b><?php echo $POD->currentUser()->nick ?></b>.  
						<a href="<? $POD->siteRoot(); ?>/logout">Logout</a>
					<?php endif ?>
				</p>
				
				<p class="input"><textarea name="comment" class="text required" id="comment"><?php echo $POD->comment ?></textarea></p>

				<?php if (!$POD->currentUser()->adminUser): ?>
					<p class='input'>
						<label id='who_are_you'>Who are you?</label>
					</p>
					<?php if ($fb_app_id && !$POD->isAuthenticated()): ?>
						<div id="fb-root"></div>
						<script>
							window.fbAsyncInit = function() {
								FB.init({
									appId			 : '<?php echo $fb_app_id ?>',
									status		 : true, 
									cookie		 : true,
									xfbml			 : true
								});
								var loadFacebookData = function() {
									FB.api('/me', function(user) {
										if (user && user.name && user.id) {
											$('input[name=comment_who_name]').val(user.name);
											$('input[name=meta_who_fb_id]').val(user.id);
											$('.fb-login-button').hide();
											$('.hide_if_fb_authd').hide();
											$('#who_are_you')
												.text('You are logged in as ' + user.name + ' via Facebook. ');

											$('#who_are_you')
												.append('<a href="javascript:FB.logout()">Logout</a>');
											
											// remove validation requirements from these forms
											$('#comment_who_name, #comment_who_email, #captcha')
												.each(function() { $(this).removeClass('required') });
										}
									});
								};
								FB.getLoginStatus(function(response) {
									loadFacebookData();
								});
								
								FB.Event.subscribe('auth.login',function(response) {
									if (/connected/i.test(response.status)) {
										loadFacebookData();
									}
								});

								FB.Event.subscribe('auth.logout',function(response) {
									$('input[name=comment_who_name]').val('');
									$('input[name=meta_who_fb_id]').val('');
									$('.fb-login-button').show();
									$('.hide_if_fb_authd').show();
									$('#who_are_you').text('Who are you?');

									// add validation requirements to this form
									$('#comment_who_name, #comment_who_email, #captcha')
										.each(function() { $(this).addClass('required') });
								});


							};
							(function(d){
								 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
								 js = d.createElement('script'); js.id = id; js.async = true;
								 js.src = "//connect.facebook.net/en_US/all.js";
								 d.getElementsByTagName('head')[0].appendChild(js);
							 }(document));
						</script>
						<div class="fb-login-button">Login with Facebook</div>
					<?php endif ?>

					<p class='input hide_if_fb_authd'>
						<label for='comment_who_name'>Your Name <span class="required">*</span></label>
						<input id='comment_who_name' name='comment_who_name' type='text' class='text required' value='<? echo $POD->comment_who_name ?>'>
					</p>
					<p class='input hide_if_fb_authd'>
						<label for='comment_who_email'>Your Email <span class="required">*</span></label>
						<input id='comment_who_email' name='comment_who_email' type='text' class='email required' value='<?php echo $POD->comment_who_email ?>'>
					</p>
					<p class='input hide_if_fb_authd'>
						<label for='captcha'>Captcha <span class="required">*</span></label>
						<input id='captcha' name='captcha' type='text required'>
					</p>
				<?php endif ?>

				<div id="comment_extras">
					<p>Are you a direct participant in this story?</P>
					<p><input type="checkbox" name="journalist" id="journalist"> <label for="journalist">I am the journalist responsible.</label></p>
					<p><input type="checkbox" name="participant" id="participant"> <label for="participant">I am mentioned in this report or was interviewed for it.</label></p>
				</div>
				<p><input type="submit" value="Post Comment" class="button" /></p>
			</form>
		<div class="clearer"></div>		
	</div>
</div>

<div class="column_4 last" id="post_info">
	
	<? $POD->output('sidebars/recent_bugs'); ?>
	
	<? $POD->output('sidebars/browse'); ?>
	
</div>

<script>

	$().ready(function() { 	
		$('#captcha').realperson({
			length: 6
		});
	});

</script>