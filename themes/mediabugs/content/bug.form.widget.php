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

$instructions_widget = $POD->getContent(array('stub'=>'instructions-widget'));
?>

<div id="bug_form">
	<form action="<? $doc->write('editpath'); ?>" method="post" id="bug" enctype="multipart/form-data" class="valid">
		<input type="hidden" name="type" value="bug" />		
		<input name="bug_target" id="media_outlet_q" type="hidden" value="<? $doc->htmlspecialwrite('suggested_outlet'); ?>"/>
		<input type="hidden" name="meta_reporter" value="<? $doc->htmlspecialwrite('reporter'); ?>" class="text" />
		<input type="hidden" name="link" value="<?= $doc->link; ?>" class="text" />
		<input type="hidden" name="meta_report_date" id="report_date" class="text required dpDate" value="<?= $doc->report_date; ?>" />
		<input type="hidden" name="meta_media_outlet_contacted" value="no"  />
		<input type="hidden" name="mode" value="widget" />
		<h1>
			Report an error in 
			<strong><? $doc->htmlspecialwrite('story_title'); ?></strong>
			<? if ($doc->reporter) {?>by <strong><? $doc->htmlspecialwrite('reporter'); ?></strong><? } ?>
			published by <strong><? $doc->htmlspecialwrite('suggested_outlet'); ?></strong>
		</h1>
						
		<div id="left">
			<p class="input">
				<label for="headline">Name This Bug</label>
				<input name="headline" id="headline" value="" length="50" class="text required" title="Required"/>					
			</p>
	
			<p class="input">
				<label for="bug_type">
					What type of bug is this?
				</label>
				<select name="meta_bug_type" id="bug_type" class="text required" title="Required">
					<option value="">Select bug category</option>
					<? foreach ($bug_types as $bug_type) { ?>
						<option value="<?= $bug_type->headline; ?>" ><?= $bug_type->headline; ?></a>
					<? } ?>	
				</select>
			</p>				

			<? $instructions_widget->output('interface_text'); ?>

		</div>
		<div id="right">
			<p class="input">
				<label for="body">
					Explain the error in this story.
				</label>
				<textarea name="body" id="bug_body" title="Required" class="text tinymce required"></textarea>
			</p>

			<p class="input" id="save_button"><input type="submit" class="button" value="Report Error" /></p>

		</div>
		<div class="clearer"></div>
		
		
	</form>

</div>
