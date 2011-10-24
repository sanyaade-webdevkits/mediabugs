<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/editform.php
* Default content add/edit form used by the core_usercontent module
* Customizing the fields in this form will alter the information stored!
* Use this file as the basis for new content type forms
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/new-content-type
/**********************************************/

$bug_types = $POD->bugTypes();

if ($doc->saved()) {
	$media_outlet = $POD->getContent(array('id'=>$doc->bug_target));
}

// after 15 minutes, users can only edit the last little piece.
$minutes = 1;
if (!$doc->saved() || $POD->currentUser()->adminUser || (time() - strtotime($doc->date) < ($minutes*60))) {
	$editable = true;
} else {
	$editable = false;
}


	$instructions_report = $POD->getContent(array('stub'=>'instructions-report-bug'));
	$instructions_what = $POD->getContent(array('stub'=>'instructions-what-bug'));
	$instructions_why = $POD->getContent(array('stub'=>'instructions-why-bug'));

	$instructions_survey = $POD->getContent(array('stub'=>'instructions-survey'));
	$instructions_survey_thanks = $POD->getContent(array('stub'=>'instructions-survey-thanks'));
	
	$outlet = $POD->getContents(array('type'=>'admin_record'))->getNext()->publication_name;
	
	$fb_app_id = $POD->getContents(array('type'=>'admin_record'))->getNext()->fb_app_id;


?>

<?php if ($_GET['msg']): ?>
<div class='error_message'><?php echo htmlspecialchars($_GET['msg']) ?></div>
<?php endif ?>
<ul id="bug_tabs">
	<? if (!$doc->saved()) { ?>
	<li id="tab_report" class="active">
		<a href="#" onclick="return tabClick('report');">Report a Bug</a>
	</li>
	<li id="tab_what">
		<a href="#" onclick="return tabClick('what');">Bug Details</a>
	</li>
	<li id="tab_why">
		<a href="#" onclick="return tabClick('why');">Supporting Info</a>
	</li>
<!--
	<li id="tab_status">
		<a href="#" onclick="return tabClick('status');">Status</a>
	</li>
-->
	<? } else if ($doc->bugIsOpen()) { ?>
		<li id="tab_edit" class="active">
			Edit Bug
		</li>
	<? } else { ?>
		<li id="tab_closed" class="active">
			Closed Bug
		</li>
	<? } ?>
	<div class="clearer"></div>
</ul>
<div id="bug_form">
	<form action="<? $doc->write('editpath'); ?>" method="post" id="bug" enctype="multipart/form-data" onsubmit="return submitBug();">
		<input type='hidden' name='meta_who_fb_id'>
		<? if ($doc->get('id')) { ?>
			<input type="hidden" name="id" id="bug_id" value="<? $doc->write('id'); ?>" />
			<input type="hidden" name="redirect" value="<? $doc->write('permalink'); ?>" />
		<? } ?>
		
		<? if ($doc->bugIsOpen()) { ?>
			<? if ($editable) { ?>		
	
				<input type="hidden" name="type" value="bug" />		
	
				<fieldset id="report">
					<legend>Report a Bug</legend>
					
					<? $instructions_report->output('interface_text'); ?>
	
		
					<? if ($doc->saved() && $POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
	
					<hr />
	
					<h3>List View</h3>
					<p>
						Since you are an administrator, you can specify a title and summary that will override the 
						values specified by the original bug reporter.
					</p>
			
					<p class="input">
						<label for="override_headline">Display Headline:</label>
						<input name="meta_override_headline" id="override_headline" value="<? $doc->htmlspecialwrite('override_headline'); ?>" class="text" title="This will appear in list view instead of the headline below" />				
					</p>		
					<p class="input">
						<label for="summary">Summary:</label>
						<textarea name="meta_summary" id="summary" title="This will appear instead of the bug explanation" class="text tinymce"><? $doc->htmlspecialwrite('summary'); ?></textarea>
					</p>		
					
					<hr />
					
					<? } ?>
	
	
	
					<p class="input">
						<label for="headline">Name This Bug <span class="required">*</span></label>
						<input name="headline" id="headline" value="<? $doc->htmlspecialwrite('headline'); ?>" length="50" class="text required" title="Try to summarize the key problem in 10 words or less"/>					
					</p>
					
					<p class="input" id="media_outlet_search">
							<label for="media_outlet">Media outlet where this story appeared:</label>
							<input name="bug_target" id="media_outlet_q" class="text required" 
								value="<?php if ($outlet) echo $outlet ?>" disabled />
							<input name="meta_bug_target" type="hidden" value="<? $doc->bug_target; ?>" id="media_outlet_id" />
					</p>
					
					<p class="input">
						<label for="reporter">
							Name(s) of who wrote or created the story, if you know.
						</label>
						<input type="text" name="meta_reporter" value="<? $doc->htmlspecialwrite('reporter'); ?>" class="text" />
					</p>
		
					<p class="input nextbutton"><a href="#who" class="littlebutton" onclick="return nextSection('report','what');">Next</a></p>
				</fieldset>
	
				<a name="what"></a>
				<fieldset id="what" style="display: none;">
					<legend>What?</legend>
				
					<? $instructions_what->output('interface_text'); ?>
				
					<p class="input">
						<label for="bug_type">
							What type of bug is this? <span class="required">*</span>
						</label>
						<select name="meta_bug_type" id="bug_type" class="text required">
							<option value="">Please pick a category for your bug</option>
							<? foreach ($bug_types as $bug_type) { ?>
								<option value="<?= $bug_type->headline; ?>" <? if ($doc->bug_type==$bug_type->headline) {?>selected<? } ?>><?= $bug_type->headline; ?></option>
							<? } ?>	
						</select>
					</p>
				
					<p class="input">
						<label for="body">
							Explain the error in this story. <span class="required">*</span>
						</label>
						<textarea name="body" id="bug_body" class="text tinymce required"><? $doc->htmlspecialwrite('body'); ?></textarea>
					</p>
	
					<p class="input">
						<label for="link">
							 If the bug is present in an online version of this story, please provide a link to the story.
						</label>
						<input type="text" name="link" value="<?= $doc->link; ?>" class="text" />
					</p>
	
					<p class="input">
						<label for="date">When did this bug appear? <span class="required">*</span></label>
						<input type="text" name="meta_report_date" id="report_date" class="text required dpDate" value="<? if ($doc->report_date) { echo date('m/d/Y',strtotime($doc->report_date)); } else { echo date("m/d/Y"); }  ?>" />
						<script type="text/javascript">
							$('#report_date').datepick({navigationAsDateFormat: true, prevText: '< M', currentText: 'M y', nextText: 'M >',changeMonth: false, changeYear: false,mandatory:true});
						</script>
					</p>
	
					<p class='input'>
						<label id='who_are_you'>Who are you? [optional]</label>
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
											$('input[name=meta_who_name]').val(user.name);
											$('input[name=meta_who_fb_id]').val(user.id);
											$('.fb-login-button').hide();
											$('.hide_if_fb_authd').hide();
											$('#who_are_you')
												.text('You are logged in as ' + user.name + ' via Facebook. ');
											$('#who_are_you')
												.append('<a href="javascript:FB.logout()">Logout</a>');
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
									$('input[name=meta_who_name]').val('');
									$('input[name=meta_who_fb_id]').val('');
									$('.fb-login-button').show();
									$('.hide_if_fb_authd').show();
									$('#who_are_you').text('Who are you? [optional]');
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
						<label for='meta_who_name'>Name</label>
						<input name='meta_who_name' type='text' class='text'>
					</p>

					<p class='input hide_if_fb_authd'>
						<label for='meta_who_email'>Email</label>
						<input name='meta_who_email' type='text' class='text'>
					</p>

					<p class='input hide_if_fb_authd'>
						<label for='captcha'>Captcha <span class="required">*</span></label>
						<input id='captcha' name='captcha' type='text' class='text'>
					</p>

					<p class="input nextbutton"><a href="#why" class="littlebutton" onclick="return nextSection('what','why');">Continue</a> <span>to attach files or other supporting evidence.</span></p>
					<div class="clearer bottom_20"></div>
					<p class="input nextbutton">.<input type="submit" class="button" value="Save Bug" /> <span>if you're done.</span></p>

				</fieldset>
	
				<a name="why"></a>
				<fieldset id="why" style="display: none;">
					<legend>Why?</legend>
					<? $instructions_why->output('interface_text'); ?>
	
	
					<p class="input">
						<label for="supporting_evidence">
							Supporting information:
						</label>
						<textarea name="meta_supporting_evidence" class="text tinymce"><? $doc->htmlspecialwrite('supporting_evidence'); ?></textarea>
					</p>
					
					<p class="input">
						<label for="file1">Attach File</label>
					</p>
					<div id="files">
						<? if ($doc->saved() && $doc->files()->count() > 0) {
							$doc->files()->output('input.file');
							?>
							<script type="text/javascript">
								FILE_COUNTER = <?= ($doc->files()->count()+1) ?>;	
							</script>
						<? } ?>
						<script type="text/javascript">
							addFile();
						</script>
					</div>
					<div class="clearer"></div>
					
					<p class="input">
						<label for="file2">&nbsp;</label>
						<a href="#" onclick="return addFile()";>Add Another File</a>
					</p>

					<p class="input" id="save_button"><input type="submit" class="button" value="Save Bug" /></p>

				</fieldset>
			
				<? $current_status = $doc->bug_status ? $doc->bug_status : 'open'; ?>
				<input type="hidden" name="meta_bug_status" value="<?= $current_status; ?>" />

			<? } else { ?>
				<p>
					This bug is no longer editable.  
				
					However, there are several ways you can add information to this bug report!
				</p>
				
				<p>
					Update the bug's status, or add information about the media outlet's response by clicking
					the edit link at the top of the bug page.
				</p>
				
				<p>
					Add other information by leaving comments.
				</p>
					
			
			<? } ?>
		<? } else { // if is open, else is closed ?>
		
			<fieldset id="survey">
				<h2>The bug <? $doc->permalink(); ?> is marked as closed.</h2>
	
				<? if (!$doc->surveyed) { ?>
					<? $instructions_survey->output('interface_text'); ?>
					
					<p class="radio">
						<label for="outcome_survey" class="label">How satisfied are you with the outcome of this bug?</label>
						<input type="radio"  class="radio required" name="outcome_survey" value="satisfied" id="oss" /> <label for="oss">I'm very satisfied</label><br />
						<input type="radio" class="radio"  name="outcome_survey" value="ok" id="oso"> <label for="oso">I'm ok with it</label><br />
						<input type="radio" class="radio" name="outcome_survey" value="not" id="osn"> <label for="osn">I'm not satisfied at all</label>
					</p>
	
					<p class="radio">
						<label class="label" for="response_survey">How satisfied are you with the response of the news outlet responsible for this bug?</label>
						<input type="radio" class="radio required"  name="response_survey" value="satisfied" id="rss"> <label for="rss">I'm very satisfied</label><br />
						<input type="radio" class="radio" name="response_survey" value="ok" id="rso"> <label for="rso">I'm ok with it</label><br />
						<input type="radio" class="radio" name="response_survey" value="not" id="rsn"> <label for="rsn">I'm not satisfied at all</label>
					</p>
	
	
					<p class="radio">
						<label class="label" for="trust_survey">Has your trust in the news outlet changed as a result of this process?</label>
						<input type="radio" class="radio required" name="trust_survey" value="more" id="tsm"> <label for="tsm">I trust it more</label><br />
						<input type="radio" class="radio" name="trust_survey" value="same" id="tss"> <label for="tss">It's the same as before</label><br />
						<input type="radio" class="radio" name="trust_survey" value="less" id="tsl"
						> <label for="tsl">I trust it less</label>
					</p>
					
					<p class="input">
						<label for="survey_comments">Do you have any further comments?</label>
						<textarea id="survey_comments" name="survey_comments" class="text tinymce."></textarea>
					</p>
					
					<p class="input">
						<input type="submit" name="survey" class="button" onclick="return validateSurvey();" value="Submit answers" />
					</p>
					<div class="clearer"></div>
				<? } else { ?>

				<? $instructions_survey_thanks->output('interface_text'); ?>
	
				<? } ?>
				<p style="text-align:center;">Does something new need to be added to this bug?  <a href="?id=<?= $doc->id; ?>&reopen=1">Re-open this bug</a>.</p>

			
			</fieldset>
		<? } // if is closed ?>
					<div id="saving_progress" style="display: none;">
						<strong>Saving...</strong>
						<br />
						<img src="<? $POD->templateDir(); ?>/img/ajax-loader.gif" align="absmiddle" />
					</div>
		</form>

</div>


<? if ($doc->saved()) { ?>
	<script type="text/javascript">
			$('fieldset').show();
			$('p.nextbutton').hide();
	</script>
<? } ?>


<script>

	$().ready(function() { 	
		$('#media_outlet_q').autocomplete('/api',{
			autoFill: false,
			selectFirst: false,
			mustMatch: false,
			extraParams: { method: 'bugtargetautocomplete' }
		}).result(function(event,data,formatted) { 
			mo_newcheck();
		});

		$('#captcha').realperson({
			length: 6
		});

	});

</script>