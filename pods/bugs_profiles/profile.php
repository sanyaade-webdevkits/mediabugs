<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_profiles/profile.php
* Handles requests to user profiles
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/messaging
/**********************************************/
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));
        $POD->header();
		
?>


<div id="widget">
    <ul id="bug_list"></ul>
</div>


<?php $POD->footer(); ?>