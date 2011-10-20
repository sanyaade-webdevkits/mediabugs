<?php
if ($logo = $POD->adminRecord->files()->contains('publication_img')) {
	// we have a logo.
} else {
	$logo = false;
}


?>
<?php if ($POD->error_msg): ?>
	<p class='error_message'><?php echo $POD->error_msg ?></p>
<?php endif ?>
<div class='grid'>
	<ul id='bug_tabs'>
		<li class='active'>&nbsp;</li>
		<div class='clearer'></div>
	</ul>
	
	<div id='bug_form'>
		<form id='spv_admin' action='<?php $POD->siteRoot()?>/spvadmin' 
				method='POST' enctype='multipart/form-data'>
			<p class='input'>
				<label for='publication_name'>Publication Name</label>
				<input name="publication_name" type='text' 
					value='<?php echo $POD->adminRecord->publication_name ?>'>
			</p>
			<p class='input'>
				<label for='publication_url'>Publication URL</label>
				<input name="publication_url" type='text'
					value='<?php echo $POD->adminRecord->publication_url ?>'>
			</p>
			<p class='input'>
				<label for='publication_img'>Publication Image</label>
				<?php if ($logo): ?>
					<img src='<?php echo $logo->src(100) ?>'>
				<?php else: ?>
					<span>(no logo has been uploaded yet)</span>
				<?php endif ?>
				<input id='publication_img' type='file' name='publication_img'>
			</p>
			
			<p class='input' id='save_button'>
				<input class='button' type='submit' value='Update Publication'>
			</p>
		</form>
	</div>
</div>

<script type='text/javascript'>
$(function() {
	$('#spv_admin').validate({
		rules: {
			publication_url: {
				url: true
			}
		}
	})
});
</script>