<?
	$doc->write('body');
	if ($doc->isEditable()) { ?>
		<a href="<?= $doc->editlink; ?>">Edit</a>
	<? } 
?>
