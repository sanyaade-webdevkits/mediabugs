<? 	$media_outlet = $POD->getContent(array('id'=>$doc->bug_target)); ?>

<p>This was sent to you by <?= $doc->send_this_sender_name; ?> (<?= $doc->send_this_sender_email; ?>):</p>

<p>Message from <?= $doc->send_this_sender_name; ?>:</p>

<?= $doc->writeFormatted('send_this_message'); ?>

<h3><a href="<?= $doc->permalink; ?>" class="bug_title" title="View this bug report"><?= $doc->bugHeadline(); ?></a></h3>

<p>A bug in <strong><?= $media_outlet->headline; ?></strong> reported to MediaBugs.org</p>

<p><?= $doc->bugSummary(); ?></p>

<hr />