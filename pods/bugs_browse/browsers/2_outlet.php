<?

Browser::addBrowseMethod('outlet','outlet_starters','Browse by Media Outlet',null,'outlet_default','outlet_browseBy','outletheader');

function outlet_starters($b) {
	$ret = array();
	
	foreach ($b->POD->bugTargets(9) as $target) { 
		$ret[$target->id] = $target->headline;
	}

	return $ret;
}

function outletheader($b) { 
	$b->outlet->output('outlet.output');
}

function outlet_default($b,$sort,$offset) {

	$POD = $b->POD;

	
	$p = '';
	if (@$_GET['a'] && strlen($_GET['a'])==1) { 
		$targets = $POD->bugTargets(10,$offset,array('or'=>array('d.headline:like'=>'the '.$_GET['a']. "%",'headline:like'=>$_GET['a']. "%")));
		$p = '&a='.$_GET['a'];
		$b->addCrumbs('<a href="' . $POD->siteRoot(false) . '/bugs/browse/outlet">Media Outlets</a>');
		$b->addCrumbs(strtoupper($_GET['a']));
	} else {					
		$b->addCrumbs('Media Outlets');
		$targets = $POD->bugTargets(10,$offset);
	}

	$b->crumbs();

	outletAlphaIndex($b->POD);
	
	$targets->output('outlet.output','header','pager',null,'No outlets found',$p);
}

function outlet_browseBy($b,$keyword,$sort,$offset) { 
	$outlet = $b->POD->getContent(array('id'=>$keyword));
	$b->outlet = $outlet;	
	$b->addCrumbs('<a href="' . $b->POD->siteRoot(false).'/bugs/browse/outlet">Media Outlets</a>');
	$b->addCrumbs($outlet->headline);
	return array('bug_target'=>$outlet->id,'bug_status:!='=>'closed:off topic');

}

function outletAlphaIndex($POD) {
	$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	echo "<ul id=\"alpha_index\">";
	foreach ($letters as $letter) { 
	
		echo "<li><a href=\"" . $POD->siteRoot(false) . "/bugs/browse/outlet?a={$letter}\">{$letter}</a></li>\n";
	}
	echo "</ul>";



}