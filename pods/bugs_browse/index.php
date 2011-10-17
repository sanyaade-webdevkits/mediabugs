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
			<? if (@$_GET['q'] || $mode=='date') { ?>

				<div class="sidebar">
					<h3>Subscribe</h3>
					<p><a href="/bugs/feeds/<?= $mode ?>?q=<?= urlencode(@$_GET['q']); ?>">Subscribe to these results via RSS</a> to receive new bugs in your feed reader.</p>
					<? if ($POD->isAuthenticated() && @$_GET['q'] && ($mode=='status' || $mode=='date' || $mode=='outlet'||$mode=='type' ||$mode=='search')) { ?>
					
						<p><?= $POD->toggleBot($subscribed,'togglesub','Stop receiving updates','E-mail me updates','method=toggleSub&keyword='.urlencode($_GET['q'])."&type=".urlencode($mode)); ?>
							whenever a bug is added to this list.
						</p>
					<? } ?>
				</div>
			<? } ?>
			
			<? $POD->output('sidebars/recent_bugs'); ?>
		
			<? $POD->output('sidebars/browse'); ?>
		
		</div>	
	<?
	
	$POD->footer();

	exit;
	


	// EVERYTHING ELSE IS DEPRECATED!!



	if ($mode == "list" || ($mode=="outlet" && !isset($_GET['q']))) {

		$crumbs[] = "<a href=\"/bugs/browse/outlet\">Media Outlets</a>";

		?>
		<div class="column_8">
			<?
			$p = '';
			if (@$_GET['a'] && strlen($_GET['a'])==1) { 
				$targets = $POD->bugTargets(10,$offset,array('or'=>array('d.headline:like'=>'the '.$_GET['a']. "%",'headline:like'=>$_GET['a']. "%")));
				$p = '&a='.$_GET['a'];
				$crumbs[] = strtoupper($_GET['a']);
			} else {					
				$targets = $POD->bugTargets(10,$offset);
			}
			?>
			<h1 id="browse_crumbs"><?= implode(" / ",$crumbs); ?></h1>

			<ul id="alpha_index">
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=a">A</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=b">B</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=c">C</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=d">D</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=e">E</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=f">F</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=g">G</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=h">H</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=i">I</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=j">J</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=k">K</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=l">L</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=m">M</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=n">N</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=o">O</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=p">P</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=q">Q</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=r">R</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=s">S</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=t">T</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=u">U</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=v">V</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=w">W</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=x">X</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=y">Y</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?a=z">Z</a></li>
			</ul>

			<?
			$targets->output('outlet.output','header','pager',null,'No outlets found',$p);
			?>
		</div>
		<div class="column_4 last">

				<? $POD->output('sidebars/recent_bugs'); ?>
				<? $POD->output('sidebars/browse'); ?>
		
		</div>	
		<?		
	
 	} else if ($mode == "home") { 
		
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

//			if (in_array($term,$POD->regionTags())) { 
			$crumbs[] = "<a href=\"/bugs/browse/tag\">Media outlets by regions</a>";
//			} else {
//			$crumbs[] = "<a href=\"/bugs/browse/tag\">Tags</a>";			
//			}
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
			<h1 id="browse_crumbs"><?= implode(" / ",$crumbs); ?></h1>
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
					$deeper = $POD->bugTargets(100);			
					foreach ($deeper as $outlet) { 					
						$links[] = $outlet->bugTargetBrowseLink();
					}
				} else if ($mode=='tag') { 
					$mode='region';
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
