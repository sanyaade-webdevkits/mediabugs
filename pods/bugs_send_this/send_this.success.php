<div id="send_this">

	<p><strong>Thank you!</strong></p>

	<p>An email has been sent to <strong><?= $doc->send_this_recipient; ?></strong> with a link to <a href="<?= $doc->permalink; ?>" class="bug_title" title="View this bug report"><?= $doc->bugHeadline(); ?></a></p>

	<p><a href="<?= $doc->permalink; ?>">Return to bug</a></p>

</div>