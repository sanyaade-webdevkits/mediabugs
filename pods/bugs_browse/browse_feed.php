<?


	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));


	$asc2uni = Array();
	for($i=128;$i<256;$i++){
	  $asc2uni[chr($i)] = "&#x".dechex($i).";";   
	}
	require_once("class.rss.php");
	include_once("browser.php");


	$mode = $_GET['mode'] ? $_GET['mode'] : 'home';
	$sort = $_GET['sort'] ? $_GET['sort'] : 'date';


	$term = $_GET['q'];
	

	// load all potential browse modes.  
	$dir = dirname(__FILE__).'/browsers/';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	if (!is_dir($file) && preg_match("/\.php/",$file)) {
	        	include_once($dir . $file);
	       	} 		
        }
        closedir($dh);
    }

	$B = new Browser($POD);

	$query = $B->browseBy($mode,$term,$sort,0,true);
	$DOCS = $B->query($query,$sort,0);
	
	$title = "Bugs by {$mode}";
	
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


	// ****************************************************************************//
	// FEED HELPER FUNCTIONS
	// ****************************************************************************//

	
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


?>
