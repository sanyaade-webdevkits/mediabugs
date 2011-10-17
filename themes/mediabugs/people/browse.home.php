<? 



// load bug types
$bug_types = $POD->bugTypes();

// load media outlets
// this should be sorted by most open bugs
$media_outlets = $POD->bugTargets(9);

$regions = $POD->bugTargetTags(24);

?>
<div class="column_8">

	<h1>Browse Bugs</h1>

	<ul class="directory">
		<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Browse by Date</a>
		
			<ul>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Newest Bugs</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date?sort=date:asc">Oldest Bugs</a></li>
			</ul>
			<div class="clearer"></div>
		</li>
		<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date?sort=modification">Browse by Recent Activity</a></li>
		<li>
			<a href="<? $POD->siteRoot(); ?>/bugs/browse/type">Browse by Type</a>
			<ul>
				<? foreach ($bug_types as $type) { ?>
					<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/type?q=<?= $type->headline; ?>"><?= $type->headline; ?></a></li>
				<? } ?>
			</ul>
			<div class="clearer"></div>
		</li>
		<li>
			<a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet">Browse by Media Outlet</a>
			<ul>
				<? foreach ($media_outlets as $outlet) { ?>
					<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet?q=<?= $outlet->id; ?>"><?= $outlet->headline; ?></a></li>				
				<? } ?>
			</ul>	
			<div class="clearer"></div>

		</li>
		<li>
			<? $POD->output('regionmap'); ?>            
			<a href="<? $POD->siteRoot(); ?>/bugs/browse/tag">Browse by Region</a>
			<ul>
				<? foreach ($regions as $tag) { ?>
					<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/tag?q=<?= $tag->value; ?>"><?= $tag->value; ?></a></li>				
				<? } ?>
			</ul>	
			<div class="clearer"></div>

		</li>

		<li>
			<a href="<? $POD->siteRoot(); ?>/bugs/browse/status">Browse by Status</a>
			<ul>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open">Open</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:under discussion">Open: Under Discussion</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:responded to">Open: Responded To</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:corrected">Closed: Corrected</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:withdrawn">Closed: Withdrawn</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:unresolved">Closed: Unresolved</a></li>
				<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:off topic">Off Topic</a></li>
			</ul>	
			<div class="clearer"></div>
		</li>
	</ul>			

</div>

<div class="column_4 last">
	<div class="sidebar">
		<form method="get" action="<? $POD->siteRoot(); ?>/bugs/browse/search" id="big_search">
			<p>Find bugs on a specific topic (person, event, place)</p>
			<input name="q" class="text" value="Search Bugs" style="color:#CCC;" onfocus="repairField(this,'Search Bugs');" onblur="repairField(this,'Search Bugs');">
			<input type="submit" value="Search" />
		</form>
	</div>
	
	<? $POD->output('sidebars/recent_bugs'); ?>

	<? $POD->output('sidebars/browse'); ?>

</div>