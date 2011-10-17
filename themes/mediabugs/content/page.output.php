<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/content/output.php
* Default output template for a piece of content
* Use this file as a basis for your custom content templates
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/

?>

<?
if ($doc->children()->count() > 0 || $doc->parent()) {

	if ($doc->parent()) { 
		$parent = $doc->parent();
		$links = $doc->parent()->children();
	}  else {
		$parent = $doc;
		$links = $doc->children();
	} 
	$links->sortBy('weight',true);
	
	?>
		<div id="navigator">
			<ul>
				<li><a href="<?= $parent->permalink; ?>" <? if ($doc->id==$parent->id) {?>class="active"<? } ?>><?= $parent->short_headline; ?></a></li>
				<? foreach ($links as $link) { ?>
					<li><a href="<?= $link->permalink; ?>" <? if ($doc->id==$link->id) {?>class="active"<? } ?>><?= $link->short_headline; ?></a></li>
				<? } ?>
			</ul>
			<div class="clearer"></div>
		</div>

		
<? } ?>

<div class="column_8">
	<div id="post_output">

		<h1><?= $doc->headline; ?></h1>
		
			<?= $doc->textFilters('body'); ?>
						
			<? if ($doc->isEditable()) { ?>
					<a href="<? $doc->write('editlink'); ?>" title="Edit this post" class="edit_button">Edit</a>
			<? } ?>


	</div>	
</div>

<div class="column_4 last" id="post_info">

	<? $POD->output('sidebars/recent_bugs'); ?>
	
	<? $POD->output('sidebars/browse'); ?>
</div>


