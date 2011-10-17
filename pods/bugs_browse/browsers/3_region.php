<?

Browser::addBrowseMethod('region','region_starters','Browse by Region','region_header','region_default','region_browseBy');


function region_header($b) {
	$b->POD->output('regionmap');
}
function region_starters($b) {
	$ret = array();
	
	foreach ($b->POD->bugTargetTags(24) as $target) { 
		$ret[$target->value] = $target->value;
	}

	return $ret;
}



function region_default($b,$sort,$offset) {
	$b->addCrumbs('Media outlets by region');
	 $b->crumbs();
	echo "<ul class=\"directory\">";
	$b->browseStarters('region');
	echo "</ul>";	

}

function region_browseBy($b,$keyword,$sort,$offset) { 
	
	$POD = $b->POD;
	
	// load up outlets in this region
	$regional_outlets = $POD->getContents(array('type'=>'bug_target','t.value'=>$keyword),'date desc',10000);
	$b->addCrumbs('<a href="' . $POD->siteRoot(false) . '/bugs/browse/region">Media outlets by region</a>');
	$b->addCrumbs($keyword);

 	return array('bug_target'=>$regional_outlets->extract('id'),'bug_status:!='=>'closed:off topic');

}