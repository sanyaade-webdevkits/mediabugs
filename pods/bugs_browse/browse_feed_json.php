<?


	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth']));
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
	$DOCS  = $B->query($query,$sort,0);

        $bugs= array();

        while($bug= $DOCS->getNext()){

            $stat        = $bug->bug_status;
            $temp        = $bug->asArray();
            $bug_headline= $bug->target()->headline;
            $stat_img    = $POD->templateDir(false)."/img/status_icons/".$POD->tokenize($bug->bug_status)."_50.png";
            $pub_link    = $bug->target()->bugTargetBrowseLink();

            $temp['pub_title']= $bug_headline;
            $temp['pub_link'] = $pub_link;
            $temp['stat_img'] = $stat_img;

            $bugs[]= $temp;
        }

        echo json_encode($bugs);

?>
