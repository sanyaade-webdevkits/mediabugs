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

	include_once("content_type.php"); // this defines some variables for use within this pod
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'debug'=>0));

	$mediabugs_account = $POD->anonymousAccount();


	$offset = 0;
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}

	$mode = $_GET['mode'] ? $_GET['mode'] : 'home';
	$sort = $_GET['sort'] ? $_GET['sort'] : 'date';

	$crumbs = array();
	$crumbs[] = "<a href=\"/bugs\">Browse</a>";
	$feed = null;
	if ($mode !='home') { 
		$feed = "/bugs/feeds/" .  $mode  ."?q=". urlencode($_GET['q']);
		$POD->header("Bugs by $mode",$feed);
	} else {
		$POD->header('Search MediaBugs');
	}
	if ($mode == "home") { 
		
		$POD->getPerson()->output('browse.home');
	
	} else { 
		
		$rawterm = $term = $_GET['q'];
		$rawterm = strip_tags($rawterm);
		
		if ($mode=='status') { 
		
			$crumbs[] = "<a href=\"/bugs/browse/status\">Status</a>";

			$key = 'bug_status';
			
			if ($term == "open") { 
				$key = 'bug_status:like';
				$term = 'open%';
			}
			if ($term == "closed") { 
				$key = 'bug_status:like';
				$term = 'closed%';
			}
			
		} else if ($mode=="outlet") { 
			if ($term) { 
				$outlet = $POD->getContent(array('id'=>$term));
			}
			$crumbs[] = "<a href=\"/bugs/browse/outlet\">Media Outlets</a>";
			$key = 'bug_target';
		} else if ($mode=="tag") { 

			if (in_array($term,$POD->regionTags())) { 
			$crumbs[] = "<a href=\"/bugs/browse/tag\">Regions</a>";
			} else {
			$crumbs[] = "<a href=\"/bugs/browse/tag\">Tags</a>";			
			}
			// first, get all outlets with this tag
			$regional_outlets = $POD->getContents(array('type'=>'bug_target','t.value'=>$term),'date desc',10000);

			// then, construct a parameter to find bugs from these outlets			
			$key = 'bug_target';
			$term = $regional_outlets->extract('id');
					
		} else if ($mode=="type") { 
			$crumbs[] = "<a href=\"/bugs/browse/type\">Bug Types</a>";
			$key = 'bug_type';
		} else if ($mode=="search") { 
			$crumbs[] = "<a href=\"/bugs/browse\">Search</a>";
			$key = 'or';
			$outlets = $POD->getContents(array('headline:like'=>"%{$term}%",'type'=>'bug_target'));

			$term = array('headline:like'=>"%{$term}%",'body:like'=>"%{$term}%");

			// also search the outlet database
			// if a matching outlet is found, display that as an alternative
			// Do you want to see bugs associated with "The Washington Post" instead?
			
		} else if ($mode=="date") { 
			$crumbs[] = 'By Date';
			$key = 1;
			$term = 1;
		}
		
		if ($outlet) { 
			$crumbs[] = $outlet->headline;
		} else if ($rawterm) { 

			if ($mode=='status') {
	 			$crumbs[] = ucwords(preg_replace("/\:/",": ",$rawterm));
			} else {
	 			$crumbs[] = $rawterm;
	 		}
		}
		
		$query = array(
			'type'=>'bug',
			$key=>$term,
			'!and'=>array('userId'=>$POD->anonymousAccount(),'status'=>'new')
		);
		
		
		if ($mode !='status') { 
			$query['bug_status:!='] = 'closed:off topic';
		}
		
		list($sort,$direction) = explode(":",$sort);
		if (!$direction) {
			$direction = "DESC";
		}
		
		if ($sort == "date") { 
			$sortBy = "d.date {$direction}";
		} else if ($sort=="modification") { 
			$sortBy = "d.changeDate {$direction}";
		} else if ($sort =="comments") { 
			// make sure we have a comment count variable
			$query['comment_count:!=']='null';
			
			// set it up to sort by the meta field
			$sortBy = "d_m_comment_count.value {$direction}";
		} else if ($sort =="views") { 
			// make sure we have a views value
			$query['views:!='] = 'null';
			$sortBy = "d_m_views.value {$direction}";
		}
		
//		$POD->debug(3);

		$docs = $POD->getContents($query,$sortBy,10,$offset);
		$docs->count();
		
//		$POD->debug(0);
		
		$subscribed = false;
		if ($POD->isAuthenticated()) { 
		
			$subs = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription','query_type'=>$mode,'body'=>$term));
			$subscribed = ($subs->totalCount() > 0);
		}
	
		?>
		<div class="column_8">
			<h1><?= implode(" / ",$crumbs); ?></h1>
			<? if ($outlets && $outlets->count() >0) { 
					$outlets->output('did_you_mean');
			} ?>
			<? if (!$rawterm && $mode!='date') { 
				if ($mode=='status') { 
					$deeper = $POD->bugStatuses();
					foreach ($deeper as $status) { 			
						$links[] = "<a href=\"?q={$status}\">" . ucwords(preg_replace("/\:/",": ",$status)) . "</a>";					
					}
				} else if ($mode=='type') { 
					$deeper = $POD->bugTypes();	
					foreach ($deeper as $type) { 
						$links[] = "<a href=\"?q={$type->headline}\">{$type->headline}</a>";					
					}	
				} else if ($mode=='outlet') { 
					$deeper = $POD->bugTargets(24);			
					foreach ($deeper as $outlet) { 					
						$links[] = $outlet->bugTargetBrowseLink();
					}
				} else if ($mode=='tag') { 
					$deeper = $POD->bugTargetTags(24);
					foreach ($deeper as $tag) { 
						$links[] = $tag->bugTargetTagBrowseLink();
					}
					
					$POD->output('regionmap');
				}
			
				echo '<ul class="directory">';
				echo "<li><a href=\"#\">" .ucfirst($mode) ."</a>";
				echo "<ul>";
				foreach ($links as $link) { 
					echo "<li>$link</li>";
				}
				echo '</ul></li></ul>';
			} else { 
			
				if ($outlet) { 
					$outlet->output('outlet.output');				
				}
					$docs->output('short','browse.header','pager',null,'No bugs found that match your criteria',"&q=".urlencode($_GET['q']) . "&sort={$_GET['sort']}"); 
			} ?>
		</div>	
		<div class="column_4 last">

					<? if ($rawterm || $mode=='date') { ?>
	
						<div class="sidebar">
							<h3>Subscribe</h3>
							<p><a href="/bugs/feeds/<?= $mode ?>?q=<?= urlencode($rawterm); ?>">Subscribe to these results via RSS</a> to receive new bugs in your feed reader.</p>
							<? if ($POD->isAuthenticated() && $mode && $rawterm) { ?>
							
								<p><?= $POD->toggleBot($subscribed,'togglesub','Stop receiving updates','E-mail me updates','method=toggleSub&keyword='.urlencode($rawterm)."&type=".urlencode($mode)); ?>
									whenever a bug is added to this list.
								</p>
							<? } ?>
						</div>
					<? } ?>
			
				<? $POD->output('sidebars/recent_bugs'); ?>
				<? $POD->output('sidebars/browse'); ?>
		
		</div>	
<?	}

	$POD->footer(); ?>
