<? 
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_api_simple/index.php
* Handles simple requests to /api
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme
/**********************************************/

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'debug'=>0));


		$method = $_GET['method'];
	
		$POD->tolog("API CALL METHOD: $method");
	
		
		if ($method=="updateBugStatus") { 
			
			$id = $_GET['bug'];
			$bug = $POD->getContent(array('id'=>$id));
			$bug_status = $_GET['bug_status'];
			$contacted = $_GET['contacted'];
			$response = $_GET['response'];
			$responded = $_GET['responded'];
			if ($bug->success() && ($bug->isEditable() || ($bug->id == $_COOKIE['claim']))) {

				if (!$POD->isAuthenticated() && $bug->id == $_COOKIE['claim']) { 
					$POD->changeActor(array('id'=>$POD->anonymousAccount()));
				}
				if ($bug->bug_status!=$bug_status) {
				
					if ($bug_status=='reopen') {
			
						$status = $POD->getComments(array('type'=>'status','contentId'=>$bug->id,'status:like'=>'open%'),'date desc',1);
						if ($status->count()<1) { 
							$bug_status = 'open';
						} else {
							$bug_status = $status->getNext()->status;
						}
					}
					
					$bug->changeBugStatus($bug_status);
						
					if (preg_match('/closed/i',$bug_status) && $POD->isAuthenticated() && $POD->currentUser()->adminUser && $_GET['survey']=='true') {
						if ($bug->author()->id != $POD->anonymousAccount()) {  			
							$bug->author()->sendEmail('bug_closed_by_admin',array('document'=>$bug));
						}
					}

				}

				$bug->media_outlet_contacted = $contacted;
				$bug->media_outlet_responded = $responded;

				if ($response != $bug->media_outlet_response) {
					// the response has been changed. 
					$bug->media_outlet_response = $response;
					if ($response != '') {
						// this bug is now responded to
						if (!preg_match('/closed/i',$bug_status)) {
							$bug->changeBugStatus('open:responded to');
							$bug_status='open:responded to';
						}
					}
				}
				if ($bug->media_outlet_responded=='no') { 
					$bug->media_outlet_response = null;
				}					

				
					
				$data['status'] = 'OK';
				$data['bug_status'] = $bug_status;
				if (preg_match('/open/i',$bug_status)) { 
					$data['open'] = true;
				} else {
					$data['open'] = false;
				}
				$data['display_status'] = ucwords(preg_replace("/\:/",": ",$bug_status));
				$data['icon_20'] = $POD->templateDir(false) .'/img/status_icons/' . $POD->tokenize($bug_status) . "_20.png";
				$data['icon_50'] = $POD->templateDir(false) .'/img/status_icons/' . $POD->tokenize($bug_status) . "_50.png";
				$data['media_outlet_response'] = $bug->media_outlet_response;
				$data['media_outlet_contacted'] = $bug->media_outlet_contacted;
				$data['bug'] = $bug->id;
				if ($POD->isAuthenticated()) { 
					$data['surveyed'] = $bug->surveyed;
				} else {
					$data['surveyed'] = true;
				}
				echo json_encode($data);				
			
			} else {
				echo json_encode(array('status'=>'error','error'=>'You cannot edit this bug'));
			}	
		
		}
	
	
		if ($method=="resortFeatures") { 
		
		
				$children =  $POD->interestingBugs(20,$offset);	
		
				$children->sortBy('weight',true);
				$weight = 1;
				foreach ($children as $child) { 
					$child->weight = $weight++;
				}
				
				$new_order = explode(",",$_GET['order']);

				$weight = 1;
				foreach ($new_order as $id) { 
					if ($id !='') { 
						$doc = $children->contains('id',$id);
						$doc->weight = $weight++;				
					}
				}				

				// requery.  we have to do this because this stack has cached versions of these objects
				$children->fill();
				
				// sort the items again
				$children->sortBy('weight',true);
				ob_start();
				$children->output('sortable',null,null);
				$data['html'] = ob_get_contents();						
				ob_end_clean();
				echo json_encode($data);
		
		
		
		}
	
		if ($method=="tags") { 
			$keyword = $_GET['q'];
			$sql = "SELECT distinct t.value FROM tags t inner join tagRef tr on t.id=tr.tagId INNER JOIN content c on tr.contentId=c.id WHERE c.type='bug_target' and value like '{$keyword}%' order by value asc;";
			$res = mysql_query($sql,$POD->DATABASE);
			while ($val = mysql_fetch_assoc($res)) { 
				echo  $val['value'] . "\n";	
			}
			
		}
	
		if ($method=="bugtargetautocomplete") { 
			$term = $_GET['q'];
			$docs = $POD->getContents(array('type'=>'bug_target','status'=>'approved','headline:like'=>"{$term}%"),'headline asc');
			foreach ($docs as $doc) { 
				
				echo "{$doc->headline}||{$doc->id}\n";
			
			}
		}
		if ($method=="bugtargetcheck") { 
			$outlet = $_GET['outlet'];
			$docs = $POD->getContents(array('type'=>'bug_target','headline'=>$outlet));
			if ($docs->count() > 0 ) { 
				$doc = $docs->getNext();
				echo json_encode($doc->asArray());
			} else {
				echo json_encode(array('error'=>'Not found'));
			}
		}
	if ($method=="removeFile") { 
		
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['contentId']));
				if ($doc->success()) { 
				
					$file = $doc->files()->contains('id',$_GET['fileId']);
					if ($file) { 
						$file->delete();
						if ($file->success()) { 											
	
							$doc->files()->fill();					
							ob_start();
							$doc->files()->output('input.file');
							$data['html'] = ob_get_contents();						
							ob_end_clean();
							echo json_encode($data);
							
						} else {
							echo json_encode(array('error'=>$file->error()));
						}
					} else {
						echo json_encode(array('error'=>'File not found'));					
					}
				}	
					
									
				} else {
					echo json_encode(array('error'=>$doc->error()));
				}
		
		}	
		if ($method=="addSub") { 
			if ($POD->isAuthenticated()) { 

				$subscription = $POD->getContent();
				$subscription->type = "subscription";
				if ($_GET['contentId']) { 
					// this is a comment subscription
					$doc = $POD->getContent(array('id'=>$_GET['contentId']));
					if ($doc->success()) { 
						$subscription->headline = "Changes to " . $doc->bugHeadline();
						$subscription->parentId = $doc->id;
						$subscription->save();

					} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['contentId']));					
					}
					


				} else if ($_GET['keyword']) { 
					// this is a keyword subscription
					
						if ($_GET['type']=='outlet') { 
							$outlet = $POD->getContent(array('id'=>$_GET['keyword']));
							$headline = "New bugs in " . $outlet->headline;
						
						} else if ($_GET['type'] =='status') { 
							$headline = "New bugs of status: " . $_GET['keyword'];
						
						} else if ($_GET['type'] =='type') { 
							$headline = "New bugs of type: " . $_GET['keyword'];
						
						} else { 
							$headline = "New bugs that match the term: " . $_GET['keyword'];
												}
						$subscription->headline = $headline;
					$subscription->body = $_GET['keyword'];
					$subscription->query_type = $_GET['type'];
					$subscription->save();					
				
				}		
				
				if ($subscription->saved()) { 
					echo json_encode($subscription->asArray());
				}
			
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED'));
			}	
				
		}
		if ($method=="removeSub") { 
			if ($POD->isAuthenticated()) { 
			
				$subscription = $POD->getContent(array('id'=>$_GET['subscriptionId']));
				if ($subscription->success()) {
				
					$subscription->delete();
					if ($subscription->success()) { 
						echo json_encode(array('subscribed'=>false,'id'=>$_GET['subscriptionId']));
					} else {
						echo json_encode(array('error'=>$subscription->error(),'id'=>$_GET['subscriptionId']));
					}
				} else {
						echo json_encode(array('error'=>$$subscription->error(),'id'=>$_GET['subscriptionId']));				
				}				
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED'));
			}	
		}
		
		if ($method=="toggleSub") { 
		
			// toggleBot options
			$data = array();
			$data['html_id'] = $_GET['html_id'];
			$data['on'] = $_GET['on'];
			$data['off'] = $_GET['off'];
		
		
			if ($POD->isAuthenticated()) { 

				if ($_GET['contentId']) { 
					$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','parentId'=>$_GET['contentId']));
				} else {
					$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','body'=>$_GET['keyword'],'query_type'=>$_GET['type']));
				}
				if ($subs->totalCount() > 0) { 
					$sub = $subs->getNext();
					$sub->delete();
					echo json_encode($data);
				} else { 
								
					$subscription = $POD->getContent();
					$subscription->type = "subscription";
	
					if ($_GET['contentId']) { 
						// this is a comment subscription
						$doc = $POD->getContent(array('id'=>$_GET['contentId']));
						if ($doc->success()) { 
							$subscription->headline = "Changes to " . $doc->bugHeadline();
							$subscription->parentId = $doc->id;
							$subscription->save();	
						} else {
							$data['error'] = $doc->error();
							echo json_encode($data);
						}
					
					} else if ($_GET['keyword']) { 
						// this is a keyword subscription
					
						if ($_GET['type']=='outlet') { 
							$outlet = $POD->getContent(array('id'=>$_GET['keyword']));
							$headline = "New bugs in " . $outlet->headline;
						
						} else if ($_GET['type'] =='status') { 
							$headline = "New bugs of status: " . $_GET['keyword'];
						
						} else if ($_GET['type'] =='type') { 
							$headline = "New bugs of type: " . $_GET['keyword'];
						
						} else { 
							$headline = "New bugs that match the term: " . $_GET['keyword'];
												}
						$subscription->headline = $headline;
						$subscription->body = $_GET['keyword'];
						$subscription->query_type = $_GET['type'];
						$subscription->save();					
				
					}						
			
					if ($subscription->saved()) {	
						$data['isOn'] = true;
						echo json_encode($data);
					}	
				}		
			} else {
				$data['error'] = 'Permission Denied';
				echo json_encode($data);
			}		
		
		
		}


		if ($method=="addFlag") { 
		
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['content']));
				if ($doc->success()) { 
					$flag = $_GET['flag'];
					$value = null;
					if (isset($_GET['value'])) {
						$value = $_GET['value']; 
					} 
					$doc->addFlag($flag,$POD->currentUser(),$value);
					if ($doc->success()) { 
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['content']));
					}
				} else {
					echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['content']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['content']));
			}		
		}
		if ($method=="removeFlag") { 
		
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['content']));
				if ($doc->success()) { 
					$flag = $_GET['flag'];
					$doc->removeFlag($flag,$POD->currentUser());
					if ($doc->success()) { 
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['content']));
					}
				} else {
					echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['content']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['content']));
			}		
		}	
		if ($method=="toggleFlag") { 
		
			// toggleBot options
			$data = array();
			$data['html_id'] = $_GET['html_id'];
			$data['on'] = $_GET['on'];
			$data['off'] = $_GET['off'];


			if ($POD->isAuthenticated()) {

				if ($_GET['type']=='global') { 
					$user = null;
				} else {
					$user = $POD->currentUser();
				}

				$doc = $POD->getContent(array('id'=>$_GET['content']));
				if ($doc->success()) { 
					$flag = $_GET['flag'];
					if ($doc->hasFlag($flag,$user)) { 
						$doc->removeFlag($flag,$user);
					} else {
						$doc->addFlag($flag,$POD->currentUser());
						if ($flag=='featured') { 
							$doc->addMeta('weight',0);
						}
					}
					if ($doc->success()) { 
						$data['count'] = $doc->flagCount($flag);
						$data['isOn'] = $doc->hasFlag($flag,$user);						
					} else {
						$data['error'] = $doc->error();
						$data['count'] = $doc->flagCount($flag);
						$data['isOn'] = $doc->hasFlag($flag,$user);
					}
					echo json_encode($data);
				} else {
					$data['error'] = $doc->error();
					echo json_encode($data);
				}
			} else {
				$data['error'] = 'Permission Denied';
				echo json_encode($data);
			}			
		}
		if ($method=="toggleUserFlag") { 

			// toggleBot options
			$data = array();
			$data['html_id'] = $_GET['html_id'];
			$data['on'] = $_GET['on'];
			$data['off'] = $_GET['off'];

		
			if ($POD->isAuthenticated()) {
				$user = $POD->getPerson(array('id'=>$_GET['user']));
				if ($user->success()) { 
					$flag = $_GET['flag'];
					if ($user->hasFlag($flag,$POD->currentUser())) { 
						$user->removeFlag($flag,$POD->currentUser());
					} else {
						$user->addFlag($flag,$POD->currentUser());
					}
					if ($user->success()) { 
						$data['isOn'] = $user->hasFlag($flag,$POD->currentUser());
						$data['count'] = $user->flagCount($flag);
					} else {
						$data['isOn'] = $user->hasFlag($flag,$POD->currentUser());
						$data['count'] = $user->flagCount($flag);
						$data['error'] = $user->error();
					}
					echo json_encode($data);
				} else {
					$data['error'] = $user->error();
					echo json_encode($data);
				}
			} else {
				$data['error'] = 'Permission Denied';
				echo json_encode($data);
			}			
		}
		if ($method=="toggleCommentFlag") { 

			// toggleBot options
			$data = array();
			$data['html_id'] = $_GET['html_id'];
			$data['on'] = $_GET['on'];
			$data['off'] = $_GET['off'];

		
			if ($POD->isAuthenticated()) {
				$comment = $POD->getComment(array('id'=>$_GET['comment']));
				if ($comment->success()) { 
					$flag = $_GET['flag'];
					if ($comment->hasFlag($flag,$POD->currentUser())) { 
						$comment->removeFlag($flag,$POD->currentUser());
					} else {
						$comment->addFlag($flag,$POD->currentUser());
					}
					if ($comment->success()) { 
						$data['isOn'] = $comment->hasFlag($flag,$POD->currentUser());
						$data['count'] = $comment->flagCount($flag);
					} else {
						$data['isOn'] = $comment->hasFlag($flag,$POD->currentUser());
						$data['count'] = $comment->flagCount($flag);
						$data['error'] = $comment->error();
					}
					echo json_encode($data);
				} else {
					$data['error'] = $comment->error();
					echo json_encode($data);
				}
			} else {
				$data['error'] = 'Permission Denied';
				echo json_encode($data);
			}			
		}
				
		if ($method == "markAsRead") { 
	
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) { 
					$doc->markCommentsAsRead();
					if ($doc->success()) { 
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
					}
				} else {
					echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}
		}
	
	
		if ($method == "addWatch") { 
	
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) { 
					$POD->currentUser()->addWatch($doc);
					if ($POD->currentUser()->success()) {
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$POD->currentUser()->error(),'id'=>$_GET['docId']));
					}
				} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}
		}	
		if ($method == "removeWatch") { 
	
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) { 
					$POD->currentUser()->removeWatch($doc);
					if ($POD->currentUser()->success()) {
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$POD->currentUser()->error(),'id'=>$_GET['docId']));
					}
				} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}
		}	
		if ($method=="toggleWatch") { 
		
			// toggleBot options
			$data = array();
			$data['html_id'] = $_GET['html_id'];
			$data['on'] = $_GET['on'];
			$data['off'] = $_GET['off'];
			
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['content']));
				if ($doc->success()) { 
					if ($POD->currentUser()->isWatched($doc)) { 
						$POD->currentUser()->removeWatch($doc);
					} else {
						$POD->currentUser()->addWatch($doc);
					}
					if ($doc->success()) { 
						$data['isOn'] = $POD->currentUser()->isWatched($doc);
						echo json_encode($data);
					} else {
						$data['error'] = $doc->error();
						echo json_encode($data);
					}
				} else {
					$data['error'] = $doc->error();
					echo json_encode($data);
				}
			} else {
				$data['error'] = 'Permission Denied';
				echo json_encode($data);
			}			
		}	
		if ($method=="addComment") { 
		
			if ($POD->isAuthenticated() && $POD->currentUser()->get('verificationKey')=='') {
				
				$did = $_GET['docId'];
				$comment = $_GET['comment'];
				$userId = $POD->currentUser()->get('id');
							
				$doc = $POD->getContent(array('id'=>$did));
				if ($doc->success()) {
					$comment = $doc->addComment($comment);
					if ($doc->success()) { 
						echo json_encode($comment->asArray());
					} else {
						echo json_encode(array('error'=>$comment->error(),'id'=>$_GET['docId']));
					}
				} else {
				echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
			}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED. You must be logged in and verified to comment.','id'=>$_GET['docId']));
			}		
		}
		if ($method=="getComments") { 
	
			$data = array();	
			if ($POD->isAuthenticated()) {
				
				$did = $_GET['docId'];
				$doc = $POD->getContent(array('id'=>$did));
				if ($doc->success()) {
					ob_start();
					while ($comment = $doc->comments()->getNext()) { 
							$comment->output();
					}
					$data['comments_as_html'] = ob_get_contents();
					ob_end_clean();
				} else {
					$data['error'] = $doc->error();		
				}
			} else {
					$data['error'] = 'PERMISSION DENIED';		
			}		
//			echo json_encode($data);
			echo $data['comments_as_html'];
	
		}	
		if ($method=="getCommentsSince") { 
	
			$data = array();	
			if ($POD->isAuthenticated()) {
				
				$did = $_GET['docId'];
				$lastComment = $_GET['lastComment'];
				$userId = $POD->currentUser()->get('id');
				$doc = $POD->getContent(array('id'=>$did));
				$last = 0;
				$count = 0;
				if ($doc->success()) {
					while ($last <= $lastComment) { 
						while ($comment = $doc->comments()->getNext()) { 
							$last = $comment->get('id');
						}
						$doc->comments()->fill();
						sleep(1);
						$count++;
						if ($count > 3) { 
							$data['last'] = $last;
							echo json_encode($data);
							return;
						}
					}			
					
					$doc->comments()->reset();
					$data['last'] = $last;
					$data['comments'] = $doc->comments()->asArray();
					ob_start();
					while ($comment = $doc->comments()->getNext()) { 
						if ($comment->get('id') > $lastComment) { 
							$comment->output();
						}
					}
					$data['comments_as_html'] = ob_get_contents();
					ob_end_clean();
				} else {
					$data['error'] = $doc->error();		
				}
			} else {
					$data['error'] = 'PERMISSION DENIED';		
			}		
			echo json_encode($data);
	
		}
		
		if ($method == "removeComment") { 
						
			if ($POD->isAuthenticated()) { 
				$comment = $POD->getComment(array('id'=>$_GET['comment']));
				$comment->delete();
				if ($comment->success()) { 
					echo json_encode(array('id'=>$_GET['comment']));
				} else {
					echo json_encode(array('error'=>$comment->error(),'id'=>$_GET['comment']));
				}
			} else {
					echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['comment']));
			}
		
		}
		if ($method == "deleteDocument") {
		
			$doc = $POD->getContent(array('id'=>$_GET['id']));
			if ($doc->success()) { 
				$doc->delete();
				if ($doc->success()) {
					echo json_encode(array('id'=>$_GET['id']));
				} else {
					echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['id']));
				}
			} else {
				echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['id']));
			}
		}
		if ($method == "addFavorite") { 
	
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) { 
					$POD->currentUser()->addFavorite($doc);
					if ($POD->currentUser()->success()) {
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$POD->currentUser()->error(),'id'=>$_GET['docId']));
					}
				} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}
		}	
		if ($method == "removeFavorite") { 
	
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) { 
					$POD->currentUser()->removeFavorite($doc);
					if ($POD->currentUser()->success()) {
						echo json_encode($doc->asArray());
					} else {
						echo json_encode(array('error'=>$POD->currentUser()->error(),'id'=>$_GET['docId']));
					}
				} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}
		}	
	
		if ($method == "vote") {
			if ($POD->isAuthenticated()) {
				$doc = $POD->getContent(array('id'=>$_GET['docId']));
				if ($doc->success()) {
					if ($doc->vote($_GET['vote'])) { 
						$data = $doc->asArray();
						$data['lastVote'] = $_GET['vote'];
						echo json_encode($data);					
					} else {
						echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
					}
				} else {
					echo json_encode(array('error'=>$doc->error(),'id'=>$_GET['docId']));
				}
			} else {
				echo json_encode(array('error'=>'PERMISSION DENIED','id'=>$_GET['docId']));
			}	
		}
	
	
	
?>
