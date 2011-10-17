<?

Browser::addBrowseMethod('date','date_starters','Browse by Date',null,'date_default','date_browseBy');

function date_starters($b) {

	return array(
		''=>'Newest Bugs',
		'&sort=date:asc'=>'Oldest Bugs',
		'&sort=modification'=>'Recent Activity',
	);

}


function date_default($b,$sort,$offset) {
	
	$b->addCrumbs('By Date');
	$b->browseBy('date',null,$sort,$offset);	

}

function date_browseBy($b,$keyword,$sort,$offset) { 	
	return array('bug_status:!='=>'closed:off topic');
}