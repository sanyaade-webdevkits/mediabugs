<?php
$author_name = $comment->parent()->author()->nick;
if (!empty($comment->parent()->who_name) && !$comment->parent()->author_name->adminUser) {
	$author_name = $comment->parent()->who_name;
}
?>

<div class="bug_history <? if ($comment->isOddItem){?>bug_odd<? } ?>">
	<span class="history_date">
		<?= date('M d, Y',strtotime($comment->date)); ?>
	</span>
	<span class="history_time">
		<?= date('g:i a',strtotime($comment->date)); ?>	
	</span>
	<span class="history_status">
	<?= ucwords(preg_replace("/\:/",": ",$comment->status)); ?>
	</span>
	<span class="history_icon">
		<img src="<? $POD->templateDir(); ?>/img/status_icons/<?= $POD->tokenize($comment->status); ?>_20.png" />
	</span>
	<span class="history_person">
		<b><?php echo $author_name ?></b>
	</span>
	<div class="clearer"></div>
</div>