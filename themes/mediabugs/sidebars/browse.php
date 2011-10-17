	<div class="sidebar" id="browse_starter">
			<h3><a href="<? $POD->siteRoot(); ?>/bugs" title="Browse bugs in a variety of ways">Browse Bugs</a></h3>
			<ul class="sidebar_directory">
				<li>
					<a href="<? $POD->siteRoot(); ?>/bugs/browse/date" title="See the most recent bugs">Browse by Date</a>
				</li>
				<li>
					<a href="<? $POD->siteRoot(); ?>/bugs/browse/type">Browse by Type</a>
				</li>
				<li>
					<a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet">Browse by Media Outlet</a>
				</li>
				<li>
					Browse by Status
					<ul>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" align="absmiddle" alt="Open" border="0">&nbsp;Open</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:under discussion"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_under_discussion_20.png" align="absmiddle" alt="Open: Under Discussion" border="0">&nbsp;Open: Under Discussion</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:responded to"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_responded_to_20.png" align="absmiddle" alt="Open: Responded to" border="0">&nbsp;Open: Responded to</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:corrected"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_corrected_20.png" align="absmiddle"  alt="Closed: Corrected" border="0">&nbsp;Closed: Corrected</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:withdrawn"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_withdrawn_20.png" align="absmiddle" alt="Closed: Withdrawn" border="0">&nbsp;Closed: Withdrawn</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:unresolved"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_unresolved_20.png" align="absmiddle" alt="Closed: Unresolved" border="0">&nbsp;Closed: Unresolved</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:off topic"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_off_topic_20.png" align="absmiddle" alt="Closed: Off Topic" border="0">&nbsp;Off Topic</a></li>
						<li><a href="<? $POD->siteRoot(); ?>/pages/status-explanation">What do these mean?</a></li>
					</ul>	
				</li>
			</ul>			
	
		</div>
