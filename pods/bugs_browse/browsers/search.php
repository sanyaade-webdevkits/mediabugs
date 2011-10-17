<?

Browser::addBrowseMethod('search',null,null,null,null,'search_browseBy');

function search_browseBy($b,$keyword,$sort,$offset) { 
	
	$b->addCrumbs("Search for \"" . htmlspecialchars($keyword) . "\"");
	return array('or'=>array('headline:like'=>"%{$keyword}%",'body:like'=>"%{$keyword}%"),'bug_status:!='=>'closed:off topic');

}