<?

Browser::addBrowseMethod('status','status_starters','Browse by Status',null,'status_default','status_browseBy');

function status_starters($b) {

	return array(
		'open' => 'Open',
		'open:under discussion' => 'Open: Under Discussion',
		'open:responded to' => 'Open: Responded To',
		'closed:corrected' => 'Closed: Corrected',
		'closed:withdrawn' => 'Closed: Withdrawn',
		'closed:unresolved' => 'Closed: Unresolved',
		'closed:off topic' => 'Closed: Off Topic',
	);

}


function status_default($b,$sort,$offset) {

	$b->crumbs('Status');
	echo "<ul class=\"directory\">";
	$b->browseStarters('status');
	echo "</ul>";
}

function status_browseBy($b,$keyword,$sort,$offset) { 
	$POD = $b->POD;


	$b->addCrumbs('<a href="' . $POD->siteRoot(false) . '/bugs/browse/status">Status</a>');
	$b->addCrumbs(ucwords(preg_replace("/\:/",": ",$keyword)));

	$key = 'bug_status';
	
	if ($keyword == "open") { 
		$key = 'bug_status:like';
		$keyword = 'open%';
	}
	if ($keyword == "closed") { 
		$key = 'bug_status:like';
		$keyword = 'closed%';
	}

	return array($key=>$keyword);

}