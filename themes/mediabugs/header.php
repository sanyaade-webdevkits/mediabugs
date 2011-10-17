<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/header.php
* Defines what is in the header of every page, used by $POD->header()
*
* Special variables in this file are:
* $pagetitle
* $feedurl
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><? if ($pagetitle) { echo $pagetitle . " - " . $POD->siteName(false); } else { echo $POD->siteName(false); } ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link REL="SHORTCUT ICON" HREF="<? $POD->siteRoot(); ?>/favicon.ico">
	<!--[if IE]>
		<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/excanvas/excanvas.compiled.js"></script>
	<![endif]-->

	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-1.4.2.min.js"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.min.js"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/other_libs/jquery.hoverIntent.minified.js" type="text/javascript" charset="utf-8"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/other_libs/bgiframe_2.1.1/jquery.bgiframe.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<? $POD->templateDir(); ?>/js/jquery-bt/jquery.bt.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jcaption.min.js"></script>
        <script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.template.js"></script>

	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-datepick/jquery.datepick-validation.js"></script>

	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.css" media="screen" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-datepick/flora.datepick.css" media="screen" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/styles.css" media="screen" charset="utf-8" />

	<? if ($feedurl) { ?>
		<link rel="alternate" type="application/rss+xml" title="RSS: <? if ($pagetitle) { echo $pagetitle . " - " . $POD->siteName(false); } else { echo $POD->siteName(false); } ?>" href="<? echo $feedurl; ?>" />
	<? } ?>
		<link rel="alternate" type="application/rss+xml" title="RSS: Most recent bugs from	 <? $POD->siteName();  ?>" href="<? $POD->siteRoot(); ?>/bugs/feeds/date" />
	<script type="text/javascript">
		var siteRoot = "<? $POD->siteRoot(); ?>";
		var podRoot = "<? $POD->podRoot(); ?>";
		var themeRoot = "<? $POD->templateDir(); ?>";
		var API = siteRoot + "/api";		
	</script>
	
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/javascript.js"></script>

	
</head>

<body id="body">

	<!-- begin login status -->
	<div id="top_bar">
		<div class="grid">
			<div class="column_6">
				Drag the <a href="javascript:(function(){
	  _my_script=document.createElement('SCRIPT');
	  _my_script.type='text/javascript';
	  _my_script.src='<?= $POD->siteRoot(); ?>/bookmarklet/mediabugs.js?x='+(Math.random());
	  document.getElementsByTagName('head')[0].appendChild(_my_script);
	})();" class="bookmarklet">Report an Error</a> bookmarklet to your toolbar <img src="<? $POD->templateDir(); ?>/img/snag.png" alt="Drag to toolbar!" border="0" align="absmiddle" />
			</div>
			<div class="column_6 last" id="login_status">
	
				<? if ($POD->isAuthenticated()) { ?>
					Welcome, <a href="<? $POD->currentUser()->write('permalink'); ?>" title="View My Profile"><? $POD->currentUser()->write('nick'); ?></a> |
					<a href="<? $POD->siteRoot(); ?>/logout" title="Logout">Logout</a>
				<? } else { ?>
					<a href="<? $POD->siteRoot(); ?>/login">Login</a> or <a href="<? $POD->siteRoot(); ?>/join">Create an account</a>
				<? } ?>
			</div>
			<div class="clearer"></div>
		</div>
	</div>
	<!-- end login status -->
	<!-- begin header -->
	<div id="header">
		<div class="grid">
			<div class="column_5">
				<a href="<? $POD->siteRoot(); ?>" title="MediaBugs Homepage"><img src="<? $POD->templateDir(); ?>/img/mediabugs_logo.png" id="logo" border="0" alt="MediaBugs" width="361" height="100"/></a>	
			</div>
			<div class="column_7 last" id="spreader">
		
				<div class="column_3">
					&nbsp;
				</div>
				<div class="column_4_simple last social_widgets">
					<div class="column_padding">
						<iframe src="http://www.facebook.com/plugins/like.php?href=<?= urlencode($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); ?>&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:280px; height:35px;" allowTransparency="true"></iframe>
						<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="media_bugs">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
				</div>
			</div>
			<div class="clearer"></div>
		</div>		
	</div>
	<!-- end header -->
	<!-- begin main navigation -->		
	<div id="nav">
		<div class="grid">
			<div class="column_8">				
			<ul>
				<li id="nav_home"><a href="<? $POD->siteRoot(); ?>">Home</a></li>
				<li id="nav_report"><a href="<? $POD->siteRoot(); ?>/bugs/edit">Report a bug</a></li>
				<li id="nav_browse">
					<a href="<? $POD->siteRoot(); ?>/bugs">Browse bugs</a>
					<div id="floating_browse_nav">
							<div class="col">	
								<ul>

									<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/outlet">Browse by Media Outlet</a>
									<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/date">Browse by Date</a>
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
								</ul>
							</div>
							<div class="col">	
								<ul>
									<li>
										<a href="<? $POD->siteRoot(); ?>/bugs/browse/status">Browse by Status</a>
										<ul>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_20.png" align="absmiddle" alt="Open" border="0">&nbsp;Open</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:under discussion"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_under_discussion_20.png" align="absmiddle" alt="Open: Under Discussion" border="0">&nbsp;Open: Under Discussion</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=open:responded to"><img src="<? $POD->templateDir(); ?>/img/status_icons/open_responded_to_20.png" align="absmiddle" alt="Open: Responded to" border="0">&nbsp;Open: Responded to</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:corrected"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_corrected_20.png" align="absmiddle"  alt="Closed: Corrected" border="0">&nbsp;Closed: Corrected</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:withdrawn"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_withdrawn_20.png" align="absmiddle" alt="Closed: Withdrawn" border="0">&nbsp;Closed: Withdrawn</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:unresolved"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_unresolved_20.png" align="absmiddle" alt="Closed: Unresolved" border="0">&nbsp;Closed: Unresolved</a></li>
											<li><a href="<? $POD->siteRoot(); ?>/bugs/browse/status?q=closed:off topic"><img src="<? $POD->templateDir(); ?>/img/status_icons/closed_off_topic_20.png" align="absmiddle" alt="Closed: Off Topic" border="0">&nbsp;Off Topic</a></li>
											<div class="clearer"></div>
											<li><a href="<? $POD->siteRoot(); ?>/pages/status-explanation">What do these mean?</a></li>
										</ul>	
										<div class="clearer"></div>
									</li>
								</ul>
							</div>
							<div class="map">
								<ul>
	
									<li>
										<a href="<? $POD->siteRoot(); ?>/bugs/browse/region">Browse by Region</a>
										<? $POD->output('regionmap_400'); ?>            
										<div class="clearer"></div>	
									</li>
								</ul>
							
							</div>
							<div class="clearer"></div>
					</div>	
				</li>
				<? if ($POD->isAuthenticated()) { ?>
					<li id="nav_my"><a href="<? $POD->siteRoot(); ?>/dashboard">My bugs</a></li>
				<? } ?>
				<li id="nav_about">
					<a href="<? $POD->siteRoot(); ?>/pages/about">About</a>
					<? 
						$about = $POD->getContent(array('stub'=>'about'));
						if ($about->success()) {
						$links = $about->children();
						$links->sortBy('weight',true);
					?>
					<ul>
						<? foreach ($links as $link) { ?>
							<li><a href="<?= $link->permalink; ?>"><?= $link->short_headline; ?></a></li>
						<? } ?>
					</ul>
					<? } ?>
				</li>
				<li id="nav_help"><a href="<? $POD->siteRoot(); ?>/pages/help">Help</a></li>
				<li id="nav_contact"><a href="<? $POD->siteRoot(); ?>/pages/contact">Contact</a></li>
			</ul>
			</div>
			<div class="column_4_simple last">		
				<form method="get" action="<? $POD->siteRoot(); ?>/bugs/browse/search">
					<input name="q" class="repairField" value="Find a specific bug..." onfocus="repairField(this,'Find a specific bug...');" onblur="repairField(this,'Find a specific bug...');" />
					<input name="search" value="Search" type="submit" class="littlebutton" />
				</form>
			</div>
			<div class="clearer"></div>
		</div>
	</div>
	<!-- end main navigation -->	
	<div id="main" class="content grid">
