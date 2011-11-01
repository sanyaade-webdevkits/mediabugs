<?php
require_once("./PeoplePods.php");

$POD = new PeoplePod(array(
	'authSecret'=>@$_COOKIE['pp_auth'],
	'lockdown'=>'adminUser'
));

function _p($contents) {
	echo "<p>".$contents."</p>";
}

function _pb($contents) {
	echo "<p><b>".$contents."</b></p>";
}


function _next($contents) {
	echo "<p id='next'><b>".$contents."</b></p>";
}

?>
<html>
<head>
	<title>PeoplePods Installer</title>
	<style>
		
		body { background: #F0F0F0; color: #3A3A3A;font-family: "Trebuchet MS"; }
		div#installer { margin: auto; width: 500px; background: #FFF; border-right: 3px solid #CCC; border-bottom: 3px solid #CCC; font-size: 24px; padding: 20px; }
		p { margin-top: 0px; }
		p.error { background: #FFFF99; color: #F00; } 
		p.footer { font-size: 12px; } 
		label { font-weight: bold; display: block; }
		input.text { width: 100%;font-size: 20px;}
		div.info { background: #FFFF99; padding: 10px; margin-bottom: 20px; } 
		#next { font-size: 36px; text-align: center; }
	</style>
</head>
<body>
<div id='installer'>
<?php
if ($POD->libOptions('mediabugs_onetime_setup')) {
	_p("The MediaBugs install script has already run.");
} else {
	$errored = false;

	echo "<h2>MediaBugs onetime setup.</h2>";
	
	$bug_types = array(
		'Error of Omission' => 'error-of-omission',
		'Typo, Spelling, Grammar' => 'typo-spelling-grammar',
		'Misquotation' => 'misquotation',
		'Mistaken Identity' => 'mistaken-identity',
		'Photo/Illustration Error' => 'photoillustration-error',
		'Simple Factual Error' => 'simple-factual-error',
		'Headline Problem' => 'headline-problem',
		'Fabrication' => 'fabrication',
		'Ethical Issue' => 'ethical-issue',
		'Other' => 'other',
		'Faulty Statistics or Math' => 'faulty-statistics-or-math',
	);

	_p("About to create standard bug types:");

	foreach ($bug_types as $bt =>$stub) {
		if ($POD->getContent(array('stub'=>$stub))->success()) {
			_p("$bt already exists");
		} else {
			$bugtype = $POD->getContent();

			$bugtype->headline = $bt;
			$bugtype->type = 'bug_type';
			$bugtype->stub = $stub;
			$bugtype->save();
		
			if ($bugtype->success()) {
				_p("'$bt' was created!");
			} else {
				_pb("'$bt' was not created: {$bugtype->error()}");
				$errored = true;
			}
		}
	}
	
	if ($POD->getPerson(array('nick'=>'Anonymous'))->success()) {
		_p("The anonymous user already exists!");
	} else {
		$anon_user = $POD->getPerson();
		$anon_user->nick = 'Anonymous';
		$anon_user->email = 'anonymous@example.com';
		$anon_user->password = rand();
	
		$anon_user->save();

		if ($anon_user->success()) {
			_p("The anonymous user was created!");
		} else {
			_pb("The anonymous user was not created: {$anon_user->error()}");
			$errored = true;
		}		
	}

	if ($POD->getContent(array('stub'=>'welcome-message'))->success()) {
		_p("The welcome message already exists!");
	} else {
		$msg = $POD->getContent();
		$msg->headline = 'Welcome Message';
		$msg->type = 'interface';
		$msg->body = 'This is the default welcome message for a MediaBugs site.';
		$msg->stub = 'welcome-message';
		$msg->save();
	
		if ($msg->success()) {
			_p("The welcome message was created!");
		} else {
			_pb("The welcome message was not created: {$msg->error()}");
			$errored = true;
		}
	}

	if ($POD->getContent(array('stub'=>'status-explanation'))->success()) {
		_p("The status explanation page already exists!");
	} else {
		$msg = $POD->getContent();
		$msg->headline = 'MediaBugs status explanations -- or, what do those icons mean?';
		$msg->type = 'page';
		$msg->body = "<p>MediaBugs uses a small set of categories and icons to represent the different states of a MediaBugs error report. In some cases the status changes automatically, and in others, either the person who originally reported the error or MediaBugs staff will change the status manually.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/open_20.png' alt='Open' border='0' /> Open</a></h2> <p>An open bug has been reported but not yet resolved. If the bug is filed by a registered MediaBugs participant it will immediately be displayed in our public listing of error reports. If it has been filed anonymously it will be reviewed by MediaBugs staff before being made public.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open:under discussion'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/open_under_discussion_20.png' alt='Open: Under Discussion' border='0' /> Open: Under Discussion</a></h2> <p>An open bug's status automatically changes to 'under discussion' once someone has posted a comment to it.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/open_responded_to_20.png' alt='Open: Responded To' border='0' /> Open: Responded to</a></h2> <p>An open bug's status changes to 'open: responded to' if the media outlet in question has provided a response to the error report. The change will happen automatically if anyone identifying him- or herself as a representative of that media outlet posts a comment. If you filed the error report you can also change its status to 'Responded to' if, for example, you received a response directly from the media outlet by email or through some other channel.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/closed_corrected_20.png' alt='Closed: Corrected' border='0' /> Closed: Corrected</a></h2> <p>An open bug can have its status changed to 'Closed: corrected' by the person who originally reported it (or by MediaBugs staff) if the media outlet has corrected the error or problem.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/closed_withdrawn_20.png' alt='Closed: Withdrawn' border='0' /> Closed: Withdrawn</a></h2> <p>You can change the status of a bug that you reported to 'Closed: withdrawn' if you have concluded that there's no error to be corrected after all.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/closed_unresolved_20.png' alt='Closed: Unresolved' border='0' /> Closed: Unresolved</a></h2> <p>After two months of inactivity on a bug report, MediaBugs will automatically mark it Closed: unresolved.</p> <h2><a href='{$POD->siteRoot(false)}/bugs/browse/status?q=open'><img src='{$POD->siteRoot(false)}/peoplepods/themes/mediabugs/img/status_icons/closed_off_topic_20.png' alt='Closed: Off Topic' border='0' />Off Topic</a></h2> <p>MediaBugs staff will change a bug report's status to 'off topic' for any of several reasons, most commonly in cases where the report lies outside our San Francisco Bay Area region, is not a correctable error, or is frivolous, inappropriate, or spam. Off Topic error reports will not show up in most of MediaBugs' listings and search results, but will be visible through the <a href='{$POD->siteRoot(false)}/bugs/browse/status?q=closed:off%20topic'>Off Topic listing.</a></p>";
		$msg->stub = 'status-explanation';
		$msg->save();
	
		if ($msg->success()) {
			_p("The status explanation was not created!");
		} else {
			_pb("The status explanation was not created: {$msg->error()}");
			$errored = true;
		}
	}
	
	_p("About to create textblocks for the 'Report a Bug' page");

	$textblocks = array(
		'Instructions report bug' => array(
			'instructions-report-bug',
			"Report a media bug when you think you've found a correctable error in a media report in print, broadcast, or online. Please be specific!"
		),
		'Instructions what bug' => array(
			'instructions-what-bug',
			"Please provide details about the bug you discovered, including the type of error and where the error occurred in the story."
		),
		'Instructions why bug' => array(
			'instructions-why-bug',
			"If you have further information in support of your bug report, please provide it here.. You may also attach images or other files to the bug report by uploading them below. All uploaded files will become available on your bug's page."
		)
	);
	
	foreach($textblocks as $headline => $details) {
		if ($POD->getContent(array('stub'=>$details[0]))->success()) {
			_p("'$headline' already exists!");
		} else {
			$tb = $POD->getContent();
			$tb->headline = $headline;
			$tb->type = 'interface';
			$tb->stub = $details[0];
			$tb->body = $details[1];
		
			$tb->save();
			if ($tb->success()) {
				_p("Creating '$headline': success.");
			} else {
				_pb("Creating '$headline': failed: {$tb->error()}");
				$errored = true;
			}
		}
	}
	
	$POD->loadAvailablePods();
	
	_p("About to disable irrelevant pods");
	$irrelevant_pods = array(
		'core_dashboard',
		'core_feeds',
		'core_friends',
		'core_groups',
		'core_invite',
		'core_search',
		'contenttype_document_add',
		'contenttype_document_list',
		'contenttype_document_view'
	);
	
	foreach ($irrelevant_pods as $p) {
		_p("Uninstalling $p");
		$POD->disablePOD($p);
	}
	
	_p("About to activate all the mediabugs pods... (except account creation)");
	$pods = array(
		'bugs_api',
		'akismet',
		'bugs_authentication_login',
		'bugs_browse',
		'bugs_home',
		'bugs_lists',
		'bugs_moderation',
		'bugs_send_this',
		'bugs_spv_admin',
		'bugs_tests',
		'contenttype_bug_add',
		'contenttype_bug_browse',
		'contenttype_bug_bugfeeds',
		'contenttype_bug_feeds',
		'contenttype_bug_list',
		'contenttype_bug_view',
		'contenttype_document_add',
		'contenttype_document_list',
		'contenttype_document_view',
		'core_pages'
	);
	
	foreach($pods as $p) {
		_p("Installing $p...");
		$POD->enablePOD($p);
	}
	$POD->saveLibOptions();
	if (!$POD->success()) {
		_pb("there was an error installing/disabling these pods.");
		$errored = true;
	} else {
		_p("SUCCESS!");
		$POD->processIncludes();
		$POD->writeHTACCESS(realpath("../"));
	}

	_p("About to create the files directories:");
	$directories = array(
		'./files',
		'./files/images',
		'./files/docs',
		'./files/cache'
	);
	
	foreach($directories as $dir) {
		if (file_exists($dir)) {
			_p("$dir already exists.");
		}
		else if (mkdir($dir, 0775, true)) {
			_p("$dir was successfully created.");
		} else {
			_pb("There was an error trying to create $dir.");
			$errored = true;
		}
	}

	if ($errored) {
		_p("There were problems during the install process. Fix your setup and try again");
	} else {
		$POD->setLibOptions('mediabugs_onetime_setup', '1');
		$POD->setLibOptions('currentTheme','mediabugs');
		$POD->saveLibOptions(true);
		_p("All done!");
		_next("<a href='{$POD->siteRoot(false)}/spvadmin'>NEXT &rarr;</a>");
	}
	
}
?>
</div>
</body>
</html>