<?

	$open_bugs = $POD->getContents(array('type'=>'bug','bug_target'=>$doc->id,'bug_status:like'=>'open%'));
	$closed_bugs = $POD->getContents(array('type'=>'bug','bug_target'=>$doc->id,'bug_status:like'=>'closed%'));

?>
<div id="media_outlet_sidebar">
<h3><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $doc->id; ?>"><?= $doc->headline; ?></a></h3>

<? if ($doc->link) { ?>
<p><a href="<?= $doc->link; ?>" target="_new">Visit Official Website</a></p>
<? } ?>

<p><?= $POD->pluralize($open_bugs->totalCount(),'@number open bug','@number open bugs'); ?></p>
<p><?= $POD->pluralize($closed_bugs->totalCount(),'@number closed bug','@number closed bugs'); ?></p>

</div>