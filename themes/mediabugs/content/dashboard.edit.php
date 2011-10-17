<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/short.php
* Default short template for content.
* Used by core_usercontent/list.php
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/

?>

	<div class="recent_activity">
		<div class="recent_summary">
			<a href="#" class="with_right_float" onclick="return toggleWatch(<?= $doc->id; ?>,'Track','Stop tracking',this);">Stop tracking</a>
			<a href="<?= $doc->permalink; ?>"><?= $doc->bugHeadline(); ?></a>
		</div>
	</div>
