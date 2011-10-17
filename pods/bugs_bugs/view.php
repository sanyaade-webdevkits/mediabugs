<?php

/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_usercontent/view.php
* Handles permalinks, comments and voting for this type of content
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/new-content-type
/**********************************************/


	include_once("content_type.php"); // this defines some variables for use within this pod
	include_once("../../PeoplePods.php");
	if ($_POST) {
		$lockdown = 'verified';
	} else {
		$lockdown = null;
	}
	$POD = new PeoplePod(array('debug'=>0,'lockdown'=>$lockdown,'authSecret'=>$_COOKIE['pp_auth']));

	if ($_GET['stub']) {		
		$doc = $POD->getContent(array('stub'=>$_GET['stub']));
	} else if ($_GET['id']) {
		$doc = $POD->getContent(array('id'=>$_GET['id']));	
	} else if ($_POST['id']) {
		$doc = $POD->getContent(array('id'=>$_POST['id']));	
	}
	
	$views = $doc->views;
	if (!$views) { 
		$views = 1;
	} else {
		$views++;
	}
	$doc->views = $views;
	
	if (!$doc->success()) {
		header("Status: 404 Not Found");
		echo "404 Not Found";
		exit;
	}

	if ($doc->author()->id == $POD->anonymousAccount() && $doc->status=='new') { 
		if (!($_GET['msg'] == "Bug saved!") && !($POD->isAuthenticated() && $POD->currentUser()->adminUser)) {

			$POD->header($doc->headline);
			echo "<h1>This bug is pending moderator review.</h1>";
			echo "<p>Please check back soon!</p>";
			$POD->footer();
			exit;

		}

	}

	if (isset($_POST['comment'])) {  // this is a request to post a comment

		$comment = $doc->addComment($_POST['comment']);
		if (!$comment || !$comment->success()) {
			$message = "Couldn't add comment! " . $doc->error();
		} else {
		
			$doc->comments()->fill();
			
			$cc = 0;
			foreach ($doc->comments() as $comment) { 
				if ($comment->type=='') { // just want to count plain comments 
					$cc++;
				}
			}
		
			$doc->comments()->reset();
			$doc->comment_count = $cc;

			if ($_POST['journalist']) {
				$comment->journalist = true;
			} else if ($_POST['participant']) { 
				$comment->participant = true;
			}
			
			
			if ($doc->comment_count == 1 && $doc->bug_status=='open') { 
				$doc->changeBugStatus('open:under discussion');
			}
			if ($comment->journalist) { 
				$doc->changeBugStatus('open:responded to');
			}

			header("Location: " . $doc->get('permalink') . "#" . $comment->get('id'));
			exit;
		}
	}
			
	if (isset($_GET['vote'])) { // this is a request to vote
	
		if ($POD->isAuthenticated()) {
			if (!$POD->currentUser()->getVote($doc)) {
				$doc->vote($_GET['vote']);
			 }
		} 
	
	}

	$POD->header($doc->get('headline')  );
	if (isset($message)) { 
		echo "<div class='info'>$message</div>";
	}
	if (!$POD->isAuthenticated() && $_GET['msg'] == "Bug saved!"){ 
		$POD->useCache(false);
	}
	$doc->output($output_template);
	$POD->footer();
	
?>
