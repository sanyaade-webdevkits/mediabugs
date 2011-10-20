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
	<div class='column_5'>
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
			
			<p class='input'>
				<input type='submit' value='Update Publication'>
			</p>
		</form>
	</div>
</div>