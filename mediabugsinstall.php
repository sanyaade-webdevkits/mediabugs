<?php
require_once("./PeoplePods.php");

$POD = new PeoplePod(array(
	'authSecret'=>@$_COOKIE['pp_auth'],
	'lockdown'=>'adminUser'
));

header('Content-type: text/plain')

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
	$msg->type = 'interface_text';
	$msg->body = 'This is the default welcome message for a MediaBugs site.';

	$msg->save();
	
	if ($msg->success()) {
		echo "created!";
	} else {
		echo "was not created: {$msg->error()}";
	}

	if ($errored) {
		echo "\nThere were problems during the install process. Fix your setup and try again";
	} else {
		$POD->setLibOptions('mediabugs_onetime_setup', '1');
		$POD->saveLibOptions(true);
		echo "\nAll done!";
	}
	
}