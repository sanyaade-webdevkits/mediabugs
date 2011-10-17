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
	$media_outlet = $POD->getContent(array('id'=>$doc->bug_target));

?>	<li id="<? $doc->write('id'); ?>" style="margin-bottom:10px;">	
		<span class="draghandle" style="font-size:40px; margin-right:20px; color: #CCC;">+</span>
		<a href="<?= $doc->permalink; ?>" class="bug_title" title="View this bug report"><?= $doc->bugHeadline(); ?></a>
		<span style="float:right">
		<?= $POD->toggleBot($doc->hasFlag('featured'),'togglefeatured_'. $doc->id,'Stop featuring this bug','Feature this bug','method=toggleFlag&type=global&flag=featured&content='.$doc->id); ?>
		</span>
	</li>
