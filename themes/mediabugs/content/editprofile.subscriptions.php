<div id="sub_<?= $doc->id; ?>" class="email_subscription <? if ($doc->isOddItem){?>email_subscription_odd<? } ?>">
	<a href="#" onclick="return removeSub(<?= $doc->id; ?>);" class="with_right_float"><img src="<? $POD->templateDir(); ?>/img/cancel.png" border="0" /></a>	
	<?= $doc->headline; ?>
</div>