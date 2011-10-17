<? if ($comment->type == "status") { ?>
@<?= date('h:i',strtotime($comment->date)); ?>: <?= $comment->comment; ?> 
<? } else { ?>
@<?= date('h:i',strtotime($comment->date)); ?>: <?= $comment->author()->nick; ?> said,
<?= $comment->comment; ?> 
<? } ?>

