<?


	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));


	require_once("class.rss.php");

	// ****************************************************************************//
	// FEED HELPER FUNCTIONS
	// ****************************************************************************//

	$asc2uni = Array();
	for($i=128;$i<256;$i++){
	  $asc2uni[chr($i)] = "&#x".dechex($i).";";   
	}
	
	function xmlformat($str){
		global $asc2uni;
		$str = str_replace("&", "&amp;", $str);
		$str = str_replace("<", "&lt;", $str); 
		$str = str_replace(">", "&gt;", $str); 
		$str = str_replace("'", "&apos;", $str);  
		$str = str_replace("\"", "&quot;", $str); 
		$str = str_replace("\r", "", $str);
		$str = strtr($str,$asc2uni);
		return $str;
	}

	$mode = $_GET['mode'] ? $_GET['mode'] : 'home';
	$sort = $_GET['sort'] ? $_GET['sort'] : 'date';


		$term = $_GET['q'];
		
		if ($mode=='status') { 
			$title = "By status: $term";
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
			$outlet = $POD->getContent(array('id'=>$term));
			$title = "By outlet: {$outlet->headline}";
			$key = 'bug_target';
		} else if ($mode=="type") { 
			$title = "By type: $term";
			$key = 'bug_type';
		} else if ($mode=="search") { 
			$title ="Search for \"{$term}\"";
			$key = 'or';
			$term = array('headline:like'=>"%{$term}%",'body:like'=>"%{$term}%");
			
			// also search the outlet database
			// if a matching outlet is found, display that as an alternative
			// Do you want to see bugs associated with "The Washington Post" instead?
			
		} else if ($mode=="date") { 
			$title ="By date";
			$key = 1;
			$term = 1;
		}
		
		$query = array(
			'type'=>'bug',
			'userId:!='=>$POD->anonymousAccount(),
			$key=>$term,
		);
		
		if ($mode !='status') { 
			$query['bug_status:!='] = 'closed:off topic';
		}
	
	$DOCS = $POD->getContents($query);
	
	$description = $title . " from " . $POD->siteName(false);

		$year = date("Y");
		
		$rss = new rss('utf-8');
		$rss->channel(xmlformat($description),$POD->siteRoot(false),xmlformat($description));

		$rss->language('en-us');
		$rss->copyright('Copyright '.$year . ' ' . $POD->siteName(false));
	
		$rss->startRSS();	

		while ($doc = $DOCS->getNext()) {
		
			$rss->itemTitle(xmlformat($doc->get('headline')));
			if ($doc->get('link')) { 
				$rss->itemLink($doc->get('link'));			
			} else {
				$rss->itemLink($doc->get('permalink'));
			}
			$nTimestamp = strtotime($doc->get('date'));
			$sISO8601=date('Y-m-d\Th:i:s',$nTimestamp). substr_replace(date('O',$nTimestamp),':',3,0);

			$rss->itemPubDate( $sISO8601);
	
			if ($doc->get('img')) {
				$rss->itemDescription(xmlformat('<img src="' . $doc->get('img') . '" /><br />' . $doc->get('body')));

			} else {
				$rss->itemDescription(xmlformat($doc->get('body')));
			}
			$rss->itemAuthor(xmlformat($doc->author('nick') . "<" . $doc->author('permalink') .">"));
			$rss->itemGuid($doc->get('permalink'));
			$rss->itemSource($POD->siteName(false),$POD->siteRoot(false));
			$rss->addItem();
		}
		
		header("Content-type: text/xml");
		echo $rss->RSSdone();

?>