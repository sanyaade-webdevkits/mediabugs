<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_usercontent/edit.php
* Handles the add/edit form for this type of content
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/new-content-type
/**********************************************/


	include_once("content_type.php"); // this defines some variables for use within this pod
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));

	
	
	// by default, this script will redirect to the homepage of the site.
	// this can be changed by passing in an alternative via the redirect parameter
	$redirect = $POD->siteRoot(false);
	
	$can_be_claimed = false;

	// msg will contain any error message occurred during the save
	$msg = null;
	
	if ($_POST) { 

		$is_new = false;
		if ($_POST['id']) { 
			$content = $POD->getContent(array('id'=>$_POST['id']));
			if (!$content->success()) { 
				$msg = $content->error();
			}
			if (!$content->isEditable()) { 
				$msg = "Access Denied";
			}		
		} else {

	
			// is there a user logged in?  If not, we need to login as the default 'mediabugs' account.
			
			if (!$POD->isAuthenticated()) { 
				
				$POD->changeActor(array('id'=>$POD->anonymousAccount()));
				$can_be_claimed = true;
			}

			$content = $POD->getContent();
			$is_new = true;
		}


		if (isset($_POST['survey'])) { 
		

			foreach (array('trust_survey','response_survey','outcome_survey','survey_comments') as $key) { 
				$content->addMeta($key,$_POST[$key]);
			}
			
			$content->addMeta('surveyed',time());

			$redirect = $content->editlink;


		} else {

		
			if (isset($_POST['headline'])) { 
				$content->set('headline',$_POST['headline']);
			}
			
			if (isset($_POST['body'])) { 
			
				$body = $_POST['body'];

				if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')  || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) {
				
					$body = $POD->formatText($body);
				}


				$body = strip_tags($body,'<p><b><i><em><strong><ul><ol><bl><li><a>');
				$content->set('body',$body);	
			} 
			
			
			if (isset($_POST['link'])) {
				$content->set('link',$_POST['link']);	
			}
			if (isset($_POST['type'])) {
				$content->set('type',$_POST['type']);	
			}		
			if (isset($_POST['parentId'])) { 
				$content->set('parentId',$_POST['parentId']);
			}
	
			// save these fields to the database.  This will update existing records or create a new one if necessary
			$content->save(true);
			if ($content->success()) { 
				if ($is_new) { 
					$msg =  "Bug saved!";
				} else {
					$msg = "Bug updated!";
				}
				
				// now that we have real object, we can add stuff to it.
	
				// add tags from a space delimited string.  You can pass in an alternative delimter like so:
				// $content->tagsFromSTring($_POST['tags'],',');
				if (isset($_POST['tags'])) { 
					$content->tagsFromString($_POST['tags']);
				}
	
				if($is_new && $POD->currentUser()->get('updates')) { 
					// does this user want to auto subscribe to comments?
					$subscription = $POD->getContent();
					$subscription->type = "subscription";
					$subscription->headline = "Changes to " . $content->headline;
					$subscription->parentId = $content->id;
					$subscription->save();
				}
	

				// if the bug status has changed, add to the bug history.
				$bug_status = $_POST['meta_bug_status'] ? $_POST['meta_bug_status'] : 'open';
				if ($content->bug_status != $bug_status) { 
					$content->changeBugStatus($bug_status);
	/*
removed 12/10/2010
this is now handled by an AJAX powered popup on the bug page.
					
					if (preg_match('/closed/i',$bug_status) && $POD->isAuthenticated() && $POD->currentUser()->adminUser && $_POST['sendSurveyEmail']) {
						if ($content->author()->id != $POD->anonymousAccount()) {  			
							$content->author()->sendEmail('bug_closed_by_admin',array('document'=>$content));
						}
					}
	
*/	
					
				}			

	/*
removed 12/10/2010
this is now handled by an AJAX powered popup on the bug page.
			
	
				// if a response has been posted, change status to responded to
				if ($_POST['meta_media_outlet_response'] && !$content->media_outlet_response) { 
					$content->changeBugStatus('open:responded to');			
				}
*/

	
				// now we'll add any meta fields that have been passed in.
				// we do this by looking for anything with a field name starting with meta_
				// so if you want to add a meta field called foo to your content
				// you'll pass in the value via meta_foo
				foreach ($_POST as $key=>$value) { 
					if (preg_match("/^meta_(.*)/",$key,$match)) { 
					
						$key = $match[1];
					
						if ($key == 'bug_status') { 
							continue;
						}
						if ($key == 'supporting_evidence') { 
							$value = strip_tags($value,'<p><b><i><em><strong><ul><ol><bl><li><a>');
						}

						// add the field.
						// the third parameter is no_html, set it to true to strip html, or false to allow html
						//$value = strip_tags($value,'<p><br><a><bl><li><ul><i><u><b><img><strike>');
						$content->addMeta($key,$value,false);
					
					}
				}
			
				// did the user specify the name of a new media outlet? If so, we need to create that record.
				if (isset($_POST['bug_target']) && $_POST['bug_target']!='') { 
					$new_o = $_POST['bug_target'];
					// first, see if it exists.
					$exists = $POD->getContents(array('headline'=>$new_o));
					if ($exists->count()>0) { 
						$content->set('bug_target',$exists->getNext()->id);
	
					} else {
						$outlet = $POD->getContent();
						$outlet->type = 'bug_target';
						$outlet->headline = $new_o;
						$outlet->format_print = ($_POST['new_media_outlet_print']==1);
						$outlet->format_online = ($_POST['new_media_outlet_online']==1);
						$outlet->format_tv = ($_POST['new_media_outlet_tv']==1);
						$outlet->format_radio = ($_POST['new_media_outlet_radio']==1);
	
						$outlet->save();
						$content->set('bug_target',$outlet->id);
						
					}
				}
	
	
				foreach ($_FILES as $filename=>$file) { 
				
					$content->addFile($file['name'],$file);
					if (!$content->success()) { 
						$msg .= ' An error occured while attaching your file: ' . $content->error();
					}
	
				}
				$content->files()->fill();
	
	
			} else {
				$msg = "Error! " . $content->error();
			}
			$redirect = $content->get('permalink');
					
			if ($_POST['redirect']) {
				if ($_POST['redirect'] == "permalink") {  	
					$redirect = $content->get('permalink');
				} else {
					$redirect = $_POST['redirect'];
				}
			}

			if ($content->bugIsClosed()) { 
				$redirect = $content->editlink;
			}


		} // if is normal edit mode		



		if ($POD->currentUser()->id==$POD->anonymousAccount() && $content->isSpam()) {
			$content->spam_type = $content->type;
			$content->type = 'spam';
			$content->save();
			
			if ($outlet) { 
				$outlet->spam_type = $outlet->type;
				$outlet->type='spam';
				$outlet->save();
			}
			$redirect = '/spam';
		}


		if ($is_new && $content->type !='spam') {
			$content->moderatorAlert('New MediaBugs bug report: ' . $content->headline);
		}

		if ($can_be_claimed) {
			setcookie('claim',$content->get('id'),0,"/");
		}

		
		if ($_POST['mode']=="widget") { 

			// reset the POD object to remove temporary mediabugs login
			$POD = new PeoplePod(array('debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));

			// reload bug
			$bug = $POD->getContent(array('id'=>$content->id));

			// output success message
			$POD->output('header.widget');
			$bug->output('bug.new_bug_instructions.widget');
			$POD->output('footer.widget');
		} else {

			header("Location: $redirect?msg=" . urlencode($msg));
		
		}
			
			
	} else if ($_GET['reopen']) {
	
		$content = $POD->getContent(array('id'=>$_GET['id']));
		if ($content->isEditable()) { 
			// find last open status
			// set it
			
			$status = $POD->getComments(array('type'=>'status','contentId'=>$content->id,'status:like'=>'open%'),'date desc',1);
			if ($status->count()<1) { 
				$status = 'open';
			} else {
				$status = $status->getNext()->status;
			}
			$content->changeBugStatus($status);
	
			header("Location: " . $content->permalink);

		} else {
			header("Location: /login");
		}
		
			
	} else if ($_GET['id']) { 
	
		$content = $POD->getContent(array('id'=>$_GET['id']));
		if (!$content->isEditable()) { 
			header("Location: $redirect?msg=" . urlencode("Access Denied"));
		} else {
		
			$POD->header("Edit " . $content->get('headline'));
			$content->output($input_template);
			$POD->footer();
		}
	} else {
	
			
				$new = $POD->getContent(array('type'=>$content_type));;

				if ($_GET['link']) { 
					$new->link = $_GET['link'];
				}

				if ($_GET['outlet']) { 
					$new->suggested_outlet = $_GET['outlet'];
				} 
			
				if ($_GET['story_title']) { 
					$new->story_title = $_GET['story_title'];
				} 
				if ($_GET['creator']) { 
					$new->reporter = $_GET['creator'];
				}
				
				if ($_GET['date']) {
					$new->report_date = $_GET['date'];
				} else {
					$new->report_date = date('Y-m-d');
				}


				if ($_GET['link'] && !$_GET['outlet']) { 
					// look for an outlet with this root url
					
					$link = $_GET['link'];
					
					$stuff = parse_url($link);
					$name = $stuff['host'];					
					$link = $stuff['scheme'] . "://" . $stuff['host'];
					

					$link = preg_replace("/\/www\./","/",$link);
					$name = preg_replace("/^www\./","",$name);
					
					$outlets = $POD->getContents(array('type'=>'bug_target','or'=>array('link'=>$link,'headline'=>$name)));
					if ($outlets->count() >0) { 
						$new->suggested_outlet = $outlets->getNext()->headline;
					} else {
						$new->suggested_outlet = $name;
					}
					
				
				}



	
			if ($_GET['mode']=="widget") { 
				
				$POD->output('header.widget');
				$new->output('bug.form.widget');
				$POD->output('footer.widget');
							
			}
                        else if($_GET['mode']== "washington"){

                                $POD->output('header.widget');
				$new->output('bug.form.washington');
				$POD->output('footer.widget');

                        }
                        else {
 	
				$POD->header("Add Something");
				$new->output($input_template);
				$POD->footer();
			}
	}	
	
?>
