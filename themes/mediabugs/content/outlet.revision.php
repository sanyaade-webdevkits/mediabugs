<h3>Corrections policy for <?= $doc->parent()->headline; ?> as of <?= date('Y-m-d',strtotime($doc->date)); ?></h3>

<p>Corrections link displayed on homepage? <strong><?= $doc->corrections_link; ?></strong></p>

<? if ($doc->corrections_link_note) { echo $doc->corrections_link_note; } ?>


<p>Corrections policy linked? <strong><?= $doc->corrections_policy; ?></strong></p>

<? if ($doc->corrections_policy_note) { echo $doc->corrections_policy_note; } ?>


<p>Explicit Instructions? <strong><?= $doc->corrections_instructions; ?></strong></p>

<? if ($doc->corrections_instructions_note) { echo $doc->corrections_instructions_note; } ?>

<? if ($doc->body) { ?>

<p>Further notes:</p>

<?= $doc->body; ?>

<? } ?>

<a href="#" onclick="return closeContentPopup();">Close</a>