<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/short.php
* Default short template for content.
* Used by core_usercontent/list.php
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
	$media_outlet = $POD->getContent(array('id'=>$doc->bug_target));

?>	<div class="bug_short <? if ($doc->get('isOddItem')) {?>content_odd<? } ?> <? if ($doc->get('isEvenItem')) {?>content_even<? } ?> <? if ($doc->get('isLastItem')) {?>content_last<? } ?> <? if ($doc->get('isFirstItem')) {?>content_first<? } ?>" id="document_<? $doc->write('id'); ?>">	
		<div class="bug_status">
			Bug #<?= $doc->id; ?>
			<a href="<?= $doc->permalink; ?>" title="<?= $doc->bug_status; ?>"><img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($doc->bug_status); ?>_50.png" width="50" height="50" border="0" alt="<?= $doc->bug_status; ?>" /></a>
		</div>

		<a href="<?= $doc->permalink; ?>" class="bug_title" title="View this bug report"><?= $doc->bugHeadline(); ?></a>
		<span class="byline">Reported by <? $doc->author()->permalink(); ?> on <strong><?= date('M j, Y',strtotime($doc->date)); ?></strong></span>
		<p><?= $doc->bugSummary(); ?></p>
		<div class="clearer"></div>
	</div>
