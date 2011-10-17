<?

	function bugsByOutlet($outlet) { 
		 $mediabugs_account = $outlet->POD->anonymousAccount();
			return $outlet->POD->getContents(array('type'=>'bug','bug_target'=>$outlet->id,'!and'=>array('userId'=>$mediabugs_account,'status'=>'new')));
	}

	function openBugsByOutlet($outlet) {
		 $mediabugs_account = $outlet->POD->anonymousAccount();
			return $outlet->POD->getContents(array('type'=>'bug','bug_target'=>$outlet->id,'bug_status:like'=>'open%','!and'=>array('userId'=>$mediabugs_account,'status'=>'new')));	
	}


	Content::registerMethod('bugsByOutlet');
	Content::registerMethod('openBugsByOutlet');
	