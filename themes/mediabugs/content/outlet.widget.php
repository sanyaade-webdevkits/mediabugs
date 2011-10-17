<?

	$img = $doc->files()->contains('file_name','img');
?>
<div id="media_outlet_widget">
	<? if ($img) { ?>
		<a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $doc->id; ?>" title="View more bugs"><img src="<?= $img->src(80); ?>" border="0" alt="Media Outlet Logo"></a>
	<? } else { ?>
		&nbsp;
	<? } ?>
</div>