<?
	
	$subject = "Your bug at MediaBugs.org has been closed.";

?>

Your bug "<?= $document->headline; ?>" is being marked "Closed: Unresolved" because it has been inactive for two months. 

Thanks for helping fix the news!

You can help even more by answering three short questions about your experience with this bug. Just click through below:

<? $document->POD->siteRoot(); ?>/r/bugs/edit?id=<?= $document->id; ?>

If you think your bug should be reopened, you can do so at the bottom of that same page.


-- the MediaBugs team 