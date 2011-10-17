<?

Browser::addBrowseMethod('type','type_starters','Browse by Type',null,'type_default','type_browseBy');

function type_starters($b) {
	$bug_types = $b->POD->bugTypes();

	$ret = array();
	foreach ($bug_types as $type) { 
		$ret[$type->headline] = $type->headline;
	}
	return $ret;
}


function type_default($b,$sort,$offset) {

	$b->crumbs('Bug Types');
	echo "<ul class=\"directory\">";
	$b->browseStarters('type');
	echo "</ul>";
}

function type_browseBy($b,$keyword,$sort,$offset) { 

	$b->addCrumbs('<a href="' . $b->POD->siteRoot(false).'/bugs/browse/type">Bug Types</a>');
	$b->addCrumbs($keyword);
	
	return array('bug_type'=>$keyword,'bug_status:!='=>'closed:off topic');

}