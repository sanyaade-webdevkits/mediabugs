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
			_pb("was not created: {$msg->error()}");
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