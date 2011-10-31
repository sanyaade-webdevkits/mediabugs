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

$POD->updated = false;

if (!empty($_POST)) {
	if (!empty($_FILES)) {
		$admin_record->addFile('publication_img', $_FILES['publication_img']);
		
		if (!$admin_record->success()) {
			$POD->error_msg = $admin_record->error();
		} else {
			$admin_record->files()->fill();
			$POD->updated = true;
		}
	}

	if (isset($_POST['publication_name'])) {
		$admin_record->publication_name = $_POST['publication_name'];
		$POD->updated = true;
	}
	
	if (isset($_POST['publication_url'])) {
		$admin_record->publication_url = $_POST['publication_url'];
		$POD->updated = true;
	}

	if (isset($_POST['fb_app_id'])) {
		$admin_record->fb_app_id = $_POST['fb_app_id'];
		$POD->updated = true;
	}

	$admin_record->save();

	if (isset($_POST['site_name'])) {
		$POD->setLibOptions('siteName',$_POST['site_name']);
		$POD->saveLibOptions();
	}

}

$POD->adminRecord = $admin_record;

$POD->header('Admin Tool');

$POD->output('admintool');

$POD->footer();