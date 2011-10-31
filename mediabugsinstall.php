<?php
require_once("./PeoplePods.php");

$POD = new PeoplePod(array(
	'authSecret'=>@$_COOKIE['pp_auth'],
	'lockdown'=>'adminUser'
));

header('Content-type: text/plain');

if ($POD->libOptions('mediabugs_onetime_setup')) {
	echo "The MediaBugs install script has already run.\n";
} else {
	$errored = false;

	echo "About to run MediaBugs onetime setup.\n";
	
	$bug_types = array(
		'Error of Omission',
		'Typo, Spelling, Grammar',
		'Misquotation',
		'Mistaken Identity',
		'Photo/Illustration Error',
		'Simple Factual Error',
		'Headline Problem',
		'Fabrication',
		'Ethical Issue',
		'Other',
		'Faulty Statistics or Math',
	);

	echo "About to create standard bug types:\n";

	foreach ($bug_types as $bt) {
		echo "\nAbout to create '$bt'...";
		$bugtype = $POD->getContent();

		$bugtype->headline = $bt;
		$bugtype->type = 'bug_type';
		
		$bugtype->save();
		
		if ($bugtype->success()) {
			echo "created!";
		} else {
			echo "was not created: {$bugtype->error()}";
			$errored = true;
		}
	}
	
	echo "\nAbout to create the 'Anonymous' user...";
	
	$anon_user = $POD->getPerson();
	$anon_user->nick = 'Anonymous';
	$anon_user->email = 'anonymous@example.com';
	$anon_user->password = rand();
	
	$anon_user->save();

	if ($anon_user->success()) {
		echo "created!";
	} else {
		echo "was not created: {$anon_user->error()}";
		$errored = true;
	}

	echo "\nAbout to create the welcome message...";
	$msg = $POD->getContent();
	$msg->headline = 'Welcome Message';
	$msg->type = 'interface';
	$msg->body = 'This is the default welcome message for a MediaBugs site.';

	$msg->save();
	
	if ($msg->success()) {
		echo "created!";
	} else {
		echo "was not created: {$msg->error()}";
		$errored = true;
	}
	
	echo "\nAbout to create textblocks for the 'Report a Bug' page";
	$textblocks = array(
		'Instructions report bug' => "Report a media bug when you think you've found a correctable error in a media report in print, broadcast, or online. Please be specific!",
		'Instructions what bug' => "Please provide details about the bug you discovered, including the type of error and where the error occurred in the story.",
		'Instructions why bug' => "If you have further information in support of your bug report, please provide it here.. You may also attach images or other files to the bug report by uploading them below. All uploaded files will become available on your bug's page."
	);
	
	foreach($textblocks as $headline => $body) {
		echo "\nCreating '$body': ";
		$tb = $POD->getContent();
		$tb->headline = $headline;
		$tb->type = 'interface';
		$tb->body = $body;
		
		$tb->save();
		if ($tb->success()) {
			echo "success.";
		} else {
			echo "failed.";
			$errored = true;
		}
	}
	
	$POD->loadAvailablePods();
	
	echo "\nAbout to disable irrelevant pods";
	$irrelevant_pods = array(
		'core_profiles',
		'core_authentication_creation'
	);
	
	foreach ($irrelevant_pods as $p) {
		echo "\nUninstalling $p";
		$POD->disablePOD($p);
	}
	
	echo "\nAbout to activate all the mediabugs pods... (except account creation)";
	$pods = array(
		'bugs_api',
		'akismet',
		'bugs_authentication_login',
		'bugs_browse',
		'bugs_dashboard',
		'bugs_home',
		'bugs_lists',
		'bugs_moderation',
		'bugs_outlets',
		'bugs_reports',
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
		'contenttype_document_view'
	);
	
	foreach($pods as $p) {
		echo "\nInstalling $p...";
		$POD->enablePOD($p);
	}
	$POD->saveLibOptions();
	if (!$POD->success()) {
		echo "\nthere was an error installing/disabling these pods.";
		$errored = true;
	} else {
		echo "\nSUCCESS!\n";
		$POD->processIncludes();
		echo $POD->writeHTACCESS(realpath("../"));
	}

	echo "\nAbout to create the files directories:";
	$directories = array(
		'./files',
		'./files/images',
		'./files/docs',
		'./files/cache'
	);
	
	foreach($directories as $dir) {
		if (file_exists($dir)) {
			echo "\n$dir already exists.";
		}
		else if (mkdir($dir, 0775, true)) {
			echo "\n$dir was successfully created.";
		} else {
			echo "\nThere was an error trying to create $dir.";
			$errored = true;
		}
	}

	if ($errored) {
		echo "\nThere were problems during the install process. Fix your setup and try again";
	} else {
		$POD->setLibOptions('mediabugs_onetime_setup', '1');
		$POD->setLibOptions('currentTheme','mediabugs');
		$POD->saveLibOptions(true);
		echo "\nAll done!";
		echo "\nYou might want to go to {$POD->siteRoot(false)}/spvadmin to set publication details";
	}
	
}