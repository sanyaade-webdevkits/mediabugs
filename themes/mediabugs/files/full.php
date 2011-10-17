<span class="img_full">
	<img src="<?= $file->src(620); ?>" alt="<? $file->htmlspecialwrite('description'); ?>" border="0"/>
	<? if ($file->description) {?><span class="caption"><? $file->write('description'); ?></span><? } ?>
	<div class="clearer"></div>
</span>
