<?

	require_once("../../PeoplePods.php");
	
	$POD = new PeoplePod(array('debug'=>0,'lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));


	if (isset($_GET['id'])) { 
		$doc = $POD->getContent(array('id'=>$_GET['id']));
	} else {
		$doc = $POD->getContent();
	}

	if (!$doc->success()) { 
		$msg = $doc->error();
	}


	if ($_POST) { 
	
	
		$doc->headline = $_POST['headline'];
		$doc->link = $_POST['link'];		
		$doc->body = $_POST['body'];		
		$doc->type = 'bug_target';
		
		$doc->format_print = ($_POST['format_print']==1);
		$doc->format_online = ($_POST['format_online']==1);
		$doc->format_tv = ($_POST['format_tv']==1);
		$doc->format_radio = ($_POST['format_radio']==1);

		$doc->save();
		
		if ($doc->success()) { 
			
			$msg = "<p>Media outlet saved!</p>";
			
			$doc->tagsFromString($_POST['tags'],",");
			
			if ($_POST['delete_logo'])  {
				if ($img = $doc->files()->contains('file_name','img')) { 
					$img->delete();
				}
			}
			
			if ($_FILES['img']) { 
				$doc->addFile('img',$_FILES['img']);
				if (!$doc->success()) { 
					$msg .= $doc->error();
				} else {
					$doc->files()->fill();
				}
			}
			
			// now deal with the correction policy information.
			if (isset($_POST['corrections_link']) || isset($_POST['corrections_policy']) || isset($_POST['corrections_instructions']) ||
				isset($_POST['corrections_link_note']) || isset($_POST['corrections_policy_note']) || isset($_POST['corrections_instructions_note']) ||
				isset($_POST['policy_body'])			
			) { 
			
				$policy = null;
				
				if ($_POST['save_policy_version']) { 
	
					$policy = $POD->getContent();
					$policy->type='policy';
					$policy->parentId = $doc->id;
					$policy->headline = date('Y-m-d');
	
				} else {
							
					$policy =  null;
					$policy_revisions = $POD->getContents(array('type'=>'policy','parentId'=>$doc->id),'date desc',1);
					if ($policy_revisions->count() > 0) {
						$policy = $policy_revisions->getNext();
					} else {
						$policy = $POD->getContent();
						$policy->type='policy';
						$policy->parentId = $doc->id;
						$policy->headline = date('Y-m-d');
					}
				
				}
				
				$policy->corrections_link = $_POST['corrections_link'];			
				$policy->corrections_link_note = $_POST['corrections_link_note'];			
	
				$policy->corrections_policy = $_POST['corrections_policy'];			
				$policy->corrections_policy_note = $_POST['corrections_policy_note'];			
	
				$policy->corrections_instructions = $_POST['corrections_instructions'];			
				$policy->corrections_instructions_note = $_POST['corrections_instructions_note'];			
	
				$policy->body = $_POST['policy_body'];
								
				$policy->save();
								
				if (!$policy->success()) { 
					$msg .= "<P>While saving the corrections policy, an error occurred: " . $policy->error() . "</p>";
				}
			}	
		
		} else {
			$msg = $doc->error();
		}


	} else if ($_GET['action'] == 'approve') { 
	
		$doc->changeStatus('approved');
		if ($doc->success()) { 
			echo json_encode(array('id'=>$doc->id));
		} else {
			echo json_encode(array('id'=>$doc->id,'error'=>$doc->error()));
		}
		exit;	
	} else if ($_GET['action'] == 'revision') { 
		if ($r = $doc->children()->contains('id',$_GET['revision'])) {
			$html = $r->render('outlet.revision');
			echo json_encode(array('html'=>$html));
		} else {
			echo json_encode(array('error'=>'Could not find revision'));
		}
		exit;
	}
	
	$POD->header('Media Outlets -> Edit');
	
	if (isset($msg)) { ?>
		<div class="info">
			<?= $msg; ?>
		</div>
	<? } 
	
	$doc->output('outlet.form');
	
	$POD->footer();