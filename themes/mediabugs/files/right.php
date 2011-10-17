<span class="img_right">
	<img src="<?= $file->src(200); ?>" alt="<? $file->htmlspecialwrite('description'); ?>" border="0"/>
	<? if ($file->description) {?><span class="caption"><? $file->write('description'); ?></span><? } ?>
</span>