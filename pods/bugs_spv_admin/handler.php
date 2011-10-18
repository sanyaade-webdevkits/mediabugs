<?php
include_once("../../lib/Core.php"); 
$POD = new PeoplePod(array(
	'authSecret' => $_COOKIE['pp_auth'],
	'lockdown' => 'adminUser'
));

// auto create an admin record if one does not already exist.
$admin_records = $POD->getContents(array(
	'type' => 'admin_record',
	'headline' => 'admin record'
));

if ($admin_records->count() == 0) {
	// need to create an admin record.
	$admin_record = $POD->getContent();
	$admin_record->set('type', 'admin_record');
	$admin_record->set('headline', 'admin record');
	
	$admin_record->save();
} else {
	$admin_record = $admin_records->getNext();
}

if (!empty($_POST)) {
	if (!empty($_FILES)) {
		$admin_record->addFile('publication_img', $_FILES['publication_img']);
		$admin_record->files()->fill();
	}

	if (isset($_POST['publication_name'])) {
		$admin_record->publication_name = $_POST['publication_name'];
	}
	
	if (isset($_POST['publication_url'])) {
		$admin_record->publication_url = $_POST['publication_url'];
	}
	$admin_record->save();

}

$POD->adminRecord = $admin_record;

$POD->header('Admin Tool');

$POD->output('admintool');

$POD->footer();