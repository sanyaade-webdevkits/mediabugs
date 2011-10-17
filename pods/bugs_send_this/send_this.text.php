<? 	$media_outlet = $POD->getContent(array('id'=>$doc->bug_target)); ?>

This was sent to you by <?= $doc->send_this_sender_name; ?> (<?= $doc->send_this_sender_email; ?>):

Message from <?= $doc->send_this_sender_name; ?>:

<?= $doc->write('send_this_message'); ?>

<?= $doc->bugHeadline(); ?>
<?= $doc->permalink; ?>

A bug in <?= $media_outlet->headline; ?> reported to MediaBugs.org

<?= $doc->bugSummary(); ?>