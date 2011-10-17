<?php

/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* dashboard/index.php
* Displays a customized "What's new" type dashboard for members
* as defined in my_theme/people/dashboard.php

* Displays a welcome page for non-members
* as defined in my_theme/people/welcome.php

* Documentation for this pod can be found here:
* http://peoplepods.net/readme
/**********************************************/


	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));

	$POD->header();
	if ($POD->isAuthenticated()) { 
		$POD->currentUser()->output('dashboard');
	} else {
		$POD->getPerson()->output('homepage');
	}	
	$POD->footer(); ?>