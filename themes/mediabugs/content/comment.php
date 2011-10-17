<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/comment.php
* Default output template for comments
* Used by core_usercontent
* 
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>
<a name="<? $comment->write('id'); ?>"></a>
<div class="comment <? if ($comment->get('isOddItem')) {?>comment_odd<? } ?> <? if ($comment->get('isEvenItem')) {?>comment_even<? } ?> <? if ($comment->get('isLastItem')) {?>comment_last<? } ?> <? if ($comment->get('isFirstItem')) {?>comment_first<? } ?> <? if($comment->journalist) { ?>comment_journalist<? } ?> <? if($comment->participant) { ?>comment_participant<? } ?>" id="comment<? $comment->write('id'); ?>">
	<? $comment->author()->output('avatar'); ?>
	<div class="comment_body">
		<span class="byline">
			<? if ($comment->POD->isAuthenticated() && ($comment->parent('userId') == $comment->POD->currentUser()->get('id') || $comment->get('userId') == $comment->POD->currentUser()->get('id'))) { ?>
				<span class="gray remove_comment"><a href="#" onclick="return removeComment(<? $comment->write('id'); ?>);">Remove Comment</a></span>
			<? } ?>
			<? $comment->author()->permalink(); ?> said:
		</span>
		<div>
		<? $comment->writeFormatted('comment') ?>
		</div>
		<span class="comment_info">
			<a href="#<?= $comment->id; ?>" title="Link to this comment"><?= date('M d, Y g:i a',strtotime($comment->date)); ?></a>
			<? if ($POD->isAuthenticated()) { ?>
				| 				
				<?= $POD->toggleBot($comment->hasFlag('report',$POD->currentUser()),'report_flag_' . $comment->id,'Flagged','Flag a problem','method=toggleCommentFlag&flag=report&comment=' . $comment->id,'multiToggleSuccess','toggleflag'); ?>
			<? } ?>
			
		</span>
	</div>
	<div class="clearer"></div>
</div>
