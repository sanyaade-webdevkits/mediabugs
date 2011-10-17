<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/stacks/header.php
* Default header used by $stack->output()
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/stack-output
/**********************************************/
?>
<div class="stack_output <? if ($title) {?>stack_<?  echo $POD->tokenize($title); } ?>">
	<div id="browse_sort">
		Sort by: <select name="sort" onchange="window.location='?<?= $additional_parameters; ?>&sort='+$(this).val();">
						<option value="date:desc" <? if ($_GET['sort']=='date' || $_GET['sort']=='date:desc') {?>selected<? } ?>>Newest</option>	
						<option value="date:asc" <? if ($_GET['sort']=='date:asc') {?>selected<? } ?>>Oldest</option>	
						<option value="modification" <? if ($_GET['sort']=='modification') {?>selected<? } ?>>Recent Activity</option>	
						<option value="comments:desc" <? if ($_GET['sort']=='comments:desc') {?>selected<? } ?>>Most Comments</option>	
						<option value="views:desc" <? if ($_GET['sort']=='views:desc') {?>selected<? } ?>>Most Views</option>	

					</select>
	</div>	
	<div class="clearer"></div>
	
