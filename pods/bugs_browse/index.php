<?php


/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_usercontent/list.php
* Handles the blog style reverse chronological list this type of content
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/new-content-type
/**********************************************/

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'debug'=>0));

	$mediabugs_account = $POD->anonymousAccount();

	include_once("browser.php");

	// load all potential browse modes.  
	$dir = dirname(__FILE__).'/browsers/';
	$includes = array();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	if (!is_dir($file) && preg_match("/\.php/",$file)) {
        		$includes[] = $dir.$file;
	       	} 		
        }
        closedir($dh);
        asort($includes);
		foreach ($includes as $file) {	
			include_once($file);
		}
    }

    
	$offset = 0;
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}

	$mode = @$_GET['mode'] ? $_GET['mode'] : 'home';
	$sort = @$_GET['sort'] ? $_GET['sort'] : 'date';

	$crumbs = array();
	$crumbs[] = "<a href=\"" . $POD->siteRoot(false) . "/bugs\">Browse Bugs</a>";
	$feed = null;
	
	$B = new Browser($POD,$crumbs);

	if ($mode !='home') { 
		$feed = "/bugs/feeds/" .  $mode  ."?q=". urlencode(@$_GET['q']);
		$POD->header("Bugs by $mode",$feed);
	} else {
		$POD->header('Search MediaBugs');
	}

	echo '<div class="column_8">';

		if ($mode=='home') {
			
			// display the browse homepage with all the various browse options
		
			echo "<h1>Browse Bugs</h1>";
			echo "<ul class=\"directory\">";
			foreach ($B->modelist() as $mode=>$stuff) {
				$B->browseStarters($mode);
			}
			echo "</ul>";
		} else {
	
			if (@$_GET['q']) { 
				// do media outlet search
				if ($mode=='search') {
					$term = $_GET['q'];
					$outlets = $POD->getContents(array('headline:like'=>"%{$term}%",'type'=>'bug_target'));
	
				}
				if ($outlets && $outlets->count() >0) { 
					$outlets->output('did_you_mean');
				}
				// print out the results
				$B->browseBy($mode,$_GET['q'],$sort,$offset);
			} else {
				// either print results, or print a secondary level of browse options
				$B->browseDefault($mode,$sort,$offset);
			}
			
		}
	echo '</div>';

	$subscribed = false;
	if ($POD->isAuthenticated() && @$_GET['q']) { 
	
		$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','query_type'=>$mode,'body'=>$_GET['q']));
		$subscribed = ($subs->totalCount() > 0);
	}

	
	?>
		<div class="column_4 last">
			<? $POD->output('sidebars/recent_bugs'); ?>
		
			<? $POD->output('sidebars/browse'); ?>
		
		</div>	
	<? $POD->footer();
