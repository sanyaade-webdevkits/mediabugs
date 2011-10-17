<?

	function anonymousAccount($POD) { 
	
		$user = $POD->getPerson(array('nick'=>'Anonymous'));
		return $user->id;
	
	
	}



	function textFilters($content,$field) { 
		
		$text = $content->customImg($field);
		$text = $content->POD->contactFormFilter($text);
		return $text;
	
	}


	function bugStatuses($POD) { 
	
	
		return array('Open','Open:Under discussion','Open:Responded to','Closed:Corrected','Closed:Withdrawn','Closed:Unresolved','Closed:Off Topic');
	
	
	}
	
	
	function regionTags($POD) { 
		return array('SF Bay Area','Greater New York','New England','Chicago','Southern California','Texas','Pacific Northwest','Washington DC');
	
	}


		
	function createTweet($content) { 
	
		$length = 140;
		$message = $content->headline;

		$length = $length - (strlen($content->shortURL())+1);
		$message = $content->POD->shorten($message,$length);
		
		return "http://twitter.com/home?status=" . urlencode($message . ' ' . $content->shortURL());	
		
	}

	
	function shortURL($doc,$service='is.gd') {

		if ($doc->shortURL) { return $doc->shortURL; }
	
		$longURL = $doc->permalink;
		switch($service) { 
			case 'is.gd':
				$shorturl = file_get_contents('http://is.gd/api.php?longurl='.$longURL);
				break;
			case 'tinyurl':
				$shorturl = file_get_contents("http://tinyurl.com/api-create.php?url=".$longURL);
				break;
		}
		
		$doc->shortURL = $shorturl;
	    return $shorturl;
	}


function bugIsOpen($bug) { 
	return (strpos($bug->bug_status,'closed')===FALSE);
}
function bugIsClosed($bug) { 
	return !$bug->bugIsOpen();
}

function changeBugStatus($bug,$status) { 

	error_log("CHANGING BUG STATUS TO {$status} FOR BUG #" . $bug->id);

	if ($bug->bug_status != $status) { 
		$bug->bug_status = $status;
		$comment = $bug->addComment('Status Changed: ' . ucfirst(preg_replace("/:/",": ",$status)),'status');
		$comment->status = $status;
	}
	
}


function bugTargetTags($POD,$count=9) { 

	return $POD->getTags(array('d.type'=>'bug_target','t.value'=>$POD->regionTags()),'t.value asc',$count);

}


function bugTargets($POD,$count=20,$offset=0,$conditions=null) {

	$query = array('type'=>'bug_target','status'=>'approved');
	if (isset($conditions)) { 
		$query = array_merge($query,$conditions);
	}
	return $POD->getContents($query,'headline asc',$count,$offset);

}

function interestingBugs($POD,$count=20,$offset=0) {
	return $POD->getContents(array('type'=>'bug','flag.name'=>'featured','weight:!='=>'null'),'d_m_weight.value+0 asc',$count,$offset);
}

function bugTypes($POD) {

	$types = $POD->getContents(array('type'=>'bug_type'),null,1000);
	$types->sortBy('weight');
	return $types;

}

function recentBugs($POD,$count=20,$offset=0,$sort='DESC') { 

	$mediabugs_account = $POD->anonymousAccount();

	return $POD->getContents(array('type'=>'bug','bug_status:!='=>'closed:off topic','!and'=>array('userId'=>$mediabugs_account,'status'=>'new')),"d.date $sort",$count,$offset);
}


function bugHeadline($bug) { 
	if ($bug->override_headline) { 
		return $bug->override_headline;
	} else {
		return $bug->headline;
	}

}
function bugSummary($bug) { 

	if ($bug->summary) {
		return strip_tags($bug->summary);
	} else {
		return $bug->shorten('body','300') . '&nbsp;<a href="' . $bug->permalink . '" class="readmore">Read more</a>';
	}

}


function target($bug) { 

	if ($bug->bug_target) { 
		return $bug->POD->getContent(array('id'=>$bug->bug_target));
	} else {
		return null;
	}

}

function bugTargetBrowseLink($outlet) { 

	return "<a href=\"" . $outlet->POD->siteRoot(false) . "/bugs/browse/outlet?q={$outlet->id}\">{$outlet->headline}</a>";
	
}

function bugTargetTagBrowseLink($tag) { 

	return "<a href=\"" . $tag->POD->siteRoot(false) . "/bugs/browse/tag?q={$tag->value}\">{$tag->value}</a>";
	
}


PeoplePod::registerMethod('recentBugs');
PeoplePod::registerMethod('bugTargets');
PeoplePod::registerMethod('bugTargetTags');

PeoplePod::registerMethod('interestingBugs');
PeoplePod::registerMethod('bugTypes');
PeoplePod::registerMethod('bugStatuses');
PeoplePod::registerMethod('anonymousAccount');

Content::registerMethod('textFilters');
Content::registerMethod('bugTargetBrowseLink');
Content::registerMethod('shortURL');
Content::registerMethod('createTweet');
Content::registerMethod('changeBugStatus');
Content::registerMethod('bugHeadline');
Content::registerMethod('bugSummary');
Content::registerMethod('bugIsOpen');
Content::registerMethod('bugIsClosed');
Content::registerMethod('target');

Tag::registerMethod('bugTargetTagBrowseLink');

PeoplePod::registerMethod('regionTags');