<? 
	$POD->registerPOD(
		'bugs_browse',
		'provides a browsing interface for bugs',
		array(
			'^bugs$'=>'bugs_browse/index.php',
			'^bugs/$'=>'bugs_browse/index.php',
			'^bugs/browse/(.*)/$'=>'bugs_browse/index.php?mode=$1',
			'^bugs/browse/(.*)'=>'bugs_browse/index.php?mode=$1',
			'^bugs/feeds/(.*)/$'=>'bugs_browse/browse_feed.php?mode=$1',		
			'^bugs/feeds/(.*)'=>'bugs_browse/browse_feed.php?mode=$1',
                        '^bugs/json/(.*)'=>'bugs_browse/browse_feed_json.php?mode=$1',
                        '^bugs/json/(.*)/$'=>'bugs_browse/browse_feed_json.php?mode=$1',

                    ),
		array()
	);

?>