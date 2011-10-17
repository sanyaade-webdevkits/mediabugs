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

		<a href="<?= $doc->permalink; ?>" class="bug_title" title="View this bug report"><?= $doc->bugHeadline(); ?></a>
		<span class="byline">Status: <?= $doc->bug_status; ?> | Submitted: <strong><?= date('M j, Y',strtotime($doc->date)); ?></strong></span>
		<?= $doc->bugSummary(); ?>
		<div class="clearer"></div>
		<a href="<?= $doc->editlink; ?>">Edit</a> | <a href="<? $POD->siteRoot(); ?>/moderation?mode=approve&id=<?= $doc->id; ?>">Approve</a> | <a href="<? $POD->siteRoot(); ?>/moderation?mode=delete&id=<?= $doc->id; ?>">Delete</a>
	</div>
