<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/editprofile.php
* used by core_authentication
* defines the edit profile page at /editprofile
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/


	$subscriptions = $POD->getContents(array('userId'=>$POD->currentUser()->id,'type'=>'subscription'),'date desc',1000.);

?>

	<div class="column_8">
					<form id="edit_profile" method="post" action="<? $POD->siteRoot(); ?>/editprofile" enctype="multipart/form-data">
	
		<fieldset>
			<legend>My Profile</legend>
				
				<? if ($user->get('verificationKey') != '') { ?>
					<div class="info">
						Your e-mail address is still unverified.  <a href="<? $POD->siteRoot(); ?>/verify">Click here</a> to verify yourself!
					</div>
				<? } // if unverified ?>
				
				
					<p class="input"><label for="nick">My Name:</label>
					<input class="required text"  maxlength="40" name="nick" id="nick" value="<? $user->htmlspecialwrite('nick'); ?>"></p>	
			
					<p class="input"><label for="email">My Email:</label>
					<input class="required validate-email text" name="email" id="email" value="<? $user->htmlspecialwrite('email'); ?>"></p>
			
					<p class="input"><label for="photo">My Picture:</label>
					<input name="img" type="file" id="img">
						<? if ($img = $user->files()->contains('file_name','img')) { ?>
		
							<br /> <img src="<? $img->write('thumbnail'); ?>"/>
		
						<? } ?>
					</p>

					<p class="input"><label for="nick">About Me:</label>
					<textarea class="text tinymce"  name="meta_aboutme" id="aboutme"><? $user->htmlspecialwrite('aboutme'); ?></textarea>

					<p class="input"><label for="nick">My Homepage:</label>
					<input class="text"  name="meta_homepage" id="homepage" value="<? $user->htmlspecialwrite('homepage'); ?>"></p>	

				<p class="input"><label>&nbsp;</label><input type="submit" class="littlebutton" value="Update my account" /></p>
		
		</fieldset>
			
		<fieldset>
			<a name="email"></a>
			<legend>Email Preferences</legend>
	
				<p class="input"><label>&nbsp;</label><input type="checkbox" value="1" name="meta_newsletter" <? if ($user->newsletter) {?>checked<? } ?> /> Send me news about MediaBugs.org</p>
				<p class="input"><label>&nbsp;</label><input type="checkbox" value="1" name="meta_updates" <? if ($user->updates) { ?>checked<? } ?> /> Send me a message when someone leaves a comment on a bug I reported</p>				
				<p class="input"><label>&nbsp;</label><input type="submit" class="littlebutton" value="Update my account" /></p>

				<div class="clearer"></div>

				<p>Receive automatic messages whenever a bug is filed on a specific media outlet or search term. Look for
				the "Email me updates" button on media outlet pages and search results.</p>

				<fieldset>
					<legend>Subscriptions</legend>
					<? $subscriptions->output('editprofile.subscriptions','header','footer',null,'No Subscriptions'); ?>
				</fieldset>
		</fieldset>
			</form>			

		<fieldset>
			<a name="connect"></a>
			<legend>Authentication Services</legend>

			<p>Facebook Connect: <a href="<? $POD->siteRoot(); ?>/facebook"><? if ($user->get('fbuid')) { ?>Connected<? } else { ?>Connect Now<? } ?></a></p>
			<p>OpenID: <a href="<? $POD->siteRoot(); ?>/openid"><? if ($user->get('openid')) { ?>Connected<? } else { ?>Connect Now<? } ?></a></p>
			
		</fieldset>
	
		<fieldset>
			<a name="password"></a>
			<legend>Change Password</legend>
			<form id="change_password" method="post" action="<? $POD->siteRoot(); ?>/editprofile">
	
				<h3>Change My Password</h3>
			
				<p class="input"><label for="password">New Pass:</label><input name="password" id="password" type="password" class="text required" /></p>
			
				<p class="input"><label>&nbsp;</label><input class="littlebutton" type="submit" value="Set New Password" /></p>	
		
			</form>
		
		</fieldset>
	
		
	</div>
	<div class="column_4 last">
		<div class="sidebar">
			
			<p><a href="<?= $user->permalink; ?>">View Profile</a>	</p>
			<p><a href="<? $POD->siteRoot(); ?>/editprofile#top">Edit Profile</a></p>
			<p><a href="<? $POD->siteRoot(); ?>/editprofile#email">Email Preferences & Subscriptions</a></p>
			<p><a href="<? $POD->siteRoot(); ?>/editprofile#connect">Authentication Services</a></p>
			<p><a href="<? $POD->siteRoot(); ?>/editprofile#password">Change Password</a></p>
		
		
		</div>
	</div>