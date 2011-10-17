<?

	$POD->registerPOD(
	
		'bugs_reports',
		'reports!',
		array(
			'^reports$'=>'bugs_reports/handler.php',
			'^reports/$'=>'bugs_reports/handler.php',
			'^reports/(.*)'=>'bugs_reports/handler.php?mode=$1'
		),
		array()
	);



?>