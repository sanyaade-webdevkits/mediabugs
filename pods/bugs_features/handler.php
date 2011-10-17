<?

	require_once("../../PeoplePods.php");
	
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'lockdown'=>'adminUser'));
	
	$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

	$features = $POD->getContents(array('type'=>'page','t.value'=>'feature'),'d.date desc',10,$offset);

	$POD->header('Features');
	
	?>
	
	<div class="column_8">
	
	
		<? $features->output('feature.list','header','pager','Features'); ?>
	
	</div>
	<div class="column_4 last">
	
		<? $POD->output('sidebars/recent_bugs'); ?>
	
		<? $POD->output('sidebars/browse'); ?>
	
	</div>

	<? $POD->footer();




?>