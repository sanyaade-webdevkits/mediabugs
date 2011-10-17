<div class="file">
	<a href="#" class="with_right_float" onclick="return removeFile(<? $file->write('contentId'); ?>,<? $file->write('id'); ?>);"><img src="<? $POD->templateDir(); ?>/img/cancel.png" border="0" /></a>

	<? if ($file->isImage()) { ?>
		<a href="<?= $file->original_file; ?>"><img src="<?= $file->thumbnail; ?>" border=0 align="absmiddle"></a>				
	<? } else { ?>
		<a href="<?= $file->original_file; ?>"><img src="<? $POD->templateDir(); ?>/img/document_stroke_32x32.png" border="0" width="32" style="padding:9px;" align="absmiddle" /></a>
	<? } ?>
	<a href="<? $file->write('original_file'); ?>"><? $file->write('original_name'); ?></a> 
</div>