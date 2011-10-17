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

?>	

	<div class="recent_activity">
		<div class="recent_comments" id="comments_<?= $doc->id; ?>" style="display:none;">
			<? $doc->output('short'); ?>
			<? $doc->goToFirstUnreadComment();
				$count = 0;
				while ($comment = $doc->comments()->getNext()) { 
					$count++;
					if ($comment->type=='status') { 
						$comment->output('bug.history');			
					} else {
						$comment->output('comment');
					}
				}
			?>
		</div>
		<div class="recent_summary" id="link_<?= $doc->id; ?>">
			<a href="#" onclick="return showComments(<?= $doc->id; ?>);" class="with_right_float"><?= $POD->pluralize($count,'@number new update','@number new updates','<span class="gray">no activity</span>'); ?></a>
			<a href="<?= $doc->permalink; ?>"><?= $doc->bugHeadline(); ?></a>
		</div>
	</div>
