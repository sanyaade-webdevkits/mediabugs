<? 

	// load latest publication info record
	$policy =  null;
	$policy_revisions = $POD->getContents(array('type'=>'policy','parentId'=>$doc->id),'date desc');
	if ($policy_revisions->count() > 0) {
		$policy = $policy_revisions->getNext();
	} else {
		$policy = $POD->getContents();
	}
?>

<? if ($doc->saved()) { ?>
	<h1><a href="/outlets">Media Outlets</a> &#187; Edit Outlet</h1>
<? } else { ?>
	<h1><a href="/outlets">Media Outlets</a> &#187; New Outlet</h1>
<? } ?>

<script src="<? $POD->templateDir(); ?>/js/jquery.tagsinput.js"></script>
<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery.tagsinput.css" />
<script type="text/javascript">
	$(document).ready(function(){
		$('#tags').tagsInput({width:'440px',height:'70px','autocomplete_url':'/api?method=tags'});
	});
</script>

<form method="post" class="valid" id="outlet_form" enctype="multipart/form-data" onsubmit="tinyMCE.triggerSave();";>

	<div class="column_6">

		<? if ($doc->status=='new') { ?>
			<div id="outlet_<?= $doc->id; ?>" class="info">
				<h3>NEW OUTLET</h3>
				<p>This media outlet was submitted to the site by a user
				and has not been approved for public display.
				It will not appear in the bug form or browse page until approved.
				</p>
				<p><a href="#" onclick="return approveOutlet(<?= $doc->id; ?>);" class="littlebutton">Approve this outlet</a></p>
				<div class="clearer"></div>
			</div>
		<? } ?>

	<fieldset>
		<legend>Outlet Info</legend>
		
		<p class="input">
			<label>Title:</label>
			<input name="headline" value="<?= htmlspecialchars($doc->headline); ?>" class="text required" />	
		</p>
		
		<p class="input">
			<label>Link:</label>
			<input name="link" value="<?= htmlspecialchars($doc->link); ?>" class="text url" />
		</p>
		
		<p class="input">
			<label>Formats:</label>
			<input type="checkbox" name="format_print" value="1" <? if ($doc->format_print) {?>checked<? } ?> /> Print		
			<input type="checkbox" name="format_online" value="1" <? if ($doc->format_online) {?>checked<? } ?> /> Online		
			<input type="checkbox" name="format_tv" value="1" <? if ($doc->format_tv) {?>checked<? } ?> /> TV
			<input type="checkbox" name="format_radio" value="1" <? if ($doc->format_radio) {?>checked<? } ?> /> Radio	
		</p>
	
		<p class="input">
			<label>Logo:</label>
			<input type="file" name="img" />
		</p>
	
		<? if ($img = $doc->files()->contains('file_name','img')) { ?>
			<p class="input">	
				<label>Current Logo:</label>
				<a href="<?= $img->original_file; ?>"><img src="<?= $img->thumbnail; ?>" border="0" /></a><Br />
				<input type="checkbox" name="delete_logo" value="1" /> Delete
			</p>				
		<? } ?>
	
		<p class="input">
			<label>Additional Information:</label>
			<textarea name="body" class="text tinymce"><?= htmlspecialchars($doc->body); ?></textarea>
		</p>
		
		
		<p class="input">
			<label>Tags</label>
			<input name="tags" id="tags" value="<?= $doc->tagsAsString(","); ?>" />
		</p>
		
		<p class="input">
			<ul id="tag_list">
				<? foreach ($POD->regionTags() as $tag) { ?>
					<li><a href="#" onclick="return $('#tags').addTag('<?= $tag; ?>');"><?= $tag; ?></a></li>
				<? } ?>
			</ul>
		</p>
	</fieldset>
	<input type="submit" name="save_policy" value="Save Outlet" />


	</div>
	<div class="column_6 last">
	<fieldset>
		<legend>Corrections Policy</legend>
		<p class="input">
			<label>Corrections link displayed on homepage and story pages?</label>
			<input type="radio" name="corrections_link" value="yes" <? if ($policy->corrections_link=='yes'){?>checked<? } ?> /> Yes
			<input type="radio" name="corrections_link" value="no" <? if ($policy->corrections_link=='no'){?>checked<? } ?> /> No
		</p>
		
		<p class="input">
			<label>More about corrections link:</label>
			<textarea name="corrections_link_note" class="text tinymce"><?= htmlspecialchars($policy->corrections_link_note); ?></textarea>
		</p>

		<p class="input">
			<label>Corrections policy posted?</label>
			<input type="radio" name="corrections_policy" value="yes" <? if ($policy->corrections_policy=='yes'){?>checked<? } ?> /> Yes
			<input type="radio" name="corrections_policy" value="no" <? if ($policy->corrections_policy=='no'){?>checked<? } ?> /> No
		</p>
		
		<p class="input">
			<label>More about corrections policy:</label>
			<textarea name="corrections_policy_note" class="text tinymce"><?= htmlspecialchars($policy->corrections_policy_note); ?></textarea>
		</p>

		<p class="input">
			<label>Explicit corrections instructions?</label>
			<input type="radio" name="corrections_instructions" value="yes" <? if ($policy->corrections_instructions=='yes'){?>checked<? } ?> /> Yes
			<input type="radio" name="corrections_instructions" value="no" <? if ($policy->corrections_instructions=='no'){?>checked<? } ?> /> No
		</p>
		
		<p class="input">
			<label>What are the explicit instructions?</label>
			<textarea name="corrections_instructions_note" class="text tinymce"><?= htmlspecialchars($policy->corrections_instructions_note); ?></textarea>
		</p>


		<p class="input">
			<label>Further Notes</label>
			<textarea name="policy_body" class="text tinymce"><?= htmlspecialchars($policy->body); ?></textarea>
		</p>
		
	</fieldset>

	<p class="input">
		<input type="submit" name="save_policy" value="Save Outlet" />
		<input type="submit" name="save_policy_version" class="with_right_float" value="Save outlet AND create a new revision of the corrections policy" />
	</p>


	<? if ($policy_revisions->count() > 1) { ?>
		<ul id="policy_revisions">
			<li class="title">Past Revisions</li>
			
			<? 
				while ($revision = $policy_revisions->getNext()) { ?>
				<li><a href="#" onclick="return viewRevision(<?= $doc->id; ?>,<?= $revision->id; ?>);"><?= $revision->headline; ?></a></li>
			<? } ?>
		</ul>
	<? } ?>

	</div>
</form>