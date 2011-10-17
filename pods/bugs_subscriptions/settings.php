<?
	$POD->registerPOD(
		'bugs_subscriptions',
		'cron job that sends subscription messages hourly and daily',
		array('^dailysubs'=>'bugs_subscriptions/daily.php','^hourlysubs'=>'bugs_subscriptions/hourly.php'),
		array()
	);


?>