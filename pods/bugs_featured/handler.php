<?

	require_once("../../PeoplePods.php");
	
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'lockdown'=>'adminUser'));
	
	
	
	$POD->header('Featured Bugs');
	?>
	
	<script src="<? $POD->templateDir(); ?>/js/dragsort/jquery.dragsort-0.3.10.min.js"></script>
	<script type="text/javascript">
	
		function saveSort() {
		
			var order = '';
			$('ul.sortable li').each(function(i,elm) { order +=  $(elm).attr('id') + ","; })
			command = API + "?method=resortFeatures&order=" + order;
		//	alert(command);
			$.getJSON(command);

		
		}
	
		$(window).ready(function(){ 
		
			$("ul").dragsort({ dragSelector: ".draghandle", dragEnd: function() { saveSort(); }, dragBetween: false, placeHolderTemplate: "<li style='padding:5px; background:#f0f0f0; border-top:1px dashed #CCC;border-bottom:1px dashed #CCC;'>&nbsp;</li>" });
		
		});
	
	</script>
	
	<h1>Sort Featured Bugs</h1>

	<p>Drag the +.  Only the first 5 will appear on the homepage.</p>
	
	<ul class="sortable" style="list-style-type:none;">
	<? 
	$interesting = $POD->interestingBugs(20,$offset);	
	$interesting->output('sortable',null,null); 

	?>
	</ul>

	<? $POD->footer();




?>