<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/list_item.php
* Used to create lists of people, mostly in sidebars

* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/
?>
<li>		
	<div class="avatar">
		<? if ($img = $user->files()->contains('file_name','img')) { ?>
			<a href="<? $user->write('permalink'); ?>"><img src="<? $img->write('thumbnail'); ?>" border="0" align="absmiddle" /></a>
		<? } ?>
	</div>
	<? $user->permalink(); ?>
	<div class="clearer"></div>
</li>