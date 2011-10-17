<? // this is the default moderator alert template.  ?>
A new bug has been published on MediaBugs!

<?= $doc->headline; ?>


In a story published by <?= $doc->target()->headline; ?>


Bug report by <?= $doc->author()->nick; ?>


Public view:
<?= $doc->permalink; ?>


Command center view:
<? $POD->podRoot(); ?>/admin/content/?id=<?= $doc->id; ?>


<? if ($doc->author()->id == $POD->anonymousAccount()) { ?>
Anonymous Bug Control:
<? $POD->podRoot(); ?>/admin/content/search.php?type=bug&userId=<?= $POD->anonymousAccount(); ?>

<? } ?>