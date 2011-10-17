<? // this is the default moderator alert template.  ?>
New content has been posted to <? $POD->siteName(); ?>


<?= $doc->headline; ?>

by <?= $doc->author()->nick; ?>


Public view:
<?= $doc->permalink; ?>


Command center view:
<? $POD->podRoot(); ?>/admin/content/?id=<?= $doc->id; ?>


View <?= $doc->author()->nick; ?>'s profile:
<?= $doc->author()->permalink; ?>


View <?= $doc->author()->nick; ?> in the command center:
<? $POD->podRoot(); ?>/admin/people/?id=<?= $doc->author()->id; ?>
