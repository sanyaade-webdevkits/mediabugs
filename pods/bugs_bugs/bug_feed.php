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


	if ($_GET['stub']) {		
		$doc = $POD->getContent(array('stub'=>$_GET['stub']));
	} else if ($_GET['id']) {
		$doc = $POD->getContent(array('id'=>$_GET['id']));	
	} else if ($_POST['id']) {
		$doc = $POD->getContent(array('id'=>$_POST['id']));	
	}
		
	$description = "Activity on " . $doc->headline;

		$year = date("Y");
		
		$rss = new rss('utf-8');
		$rss->channel(xmlformat($description),$POD->siteRoot(false),xmlformat($description));

		$rss->language('en-us');
		$rss->copyright('Copyright '.$year . ' ' . $POD->siteName(false));
	
		$rss->startRSS();	

		$doc->comments()->sortBy('date',true);
		while ($comment = $doc->comments()->getNext()) {
		
		
			if ($comment->type=="status") { 
				$rss->itemTitle(xmlformat("Status Change"));
				$rss->itemDescription(xmlformat($comment->get('comment')));
			} else {
				$rss->itemTitle(xmlformat("New Comment"));				
				$rss->itemDescription(xmlformat($comment->author()->nick . " said: " . $comment->get('comment')));
			}
			$rss->itemLink($doc->get('permalink') . "#" . $comment->id);

			$nTimestamp = strtotime($comment->get('date'));
			$sISO8601=date('Y-m-d\Th:i:s',$nTimestamp). substr_replace(date('O',$nTimestamp),':',3,0);

			$rss->itemPubDate( $sISO8601);
	
			$rss->itemAuthor(xmlformat($comment->author('nick') . "<" . $comment->author('permalink') .">"));
			$rss->itemGuid($doc->get('permalink') . "#" . $comment->id);
			$rss->itemSource($POD->siteName(false),$POD->siteRoot(false));
			$rss->addItem();
		}
		
		header("Content-type: text/xml");
		echo $rss->RSSdone();

?>