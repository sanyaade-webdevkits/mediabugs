	<script type="text/javascript" src="https://ssl.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script> 
	<script type="text/javascript">FB.init('<?= $user->write('facebook_api'); ?>','/xd_receiver.htm');</script>	

	<div id="connect_form">

			
			<h1>Connect to Facebook</h1>
			

			<? if ($user->get('logged_into_facebook')) { ?>
				<div class="connect_details">
				
					<p>
						<strong>Facebook Name:</strong> <fb:profile-pic uid=loggedinuser facebook-logo=true></fb:profile-pic> <fb:name uid=loggedinuser useyou=false></fb:name>
						&nbsp;&nbsp;<a href="<? $POD->siteRoot(); ?>/logout?nobounce=1" onclick="FB.Connect.logout(function(){window.location='<? $POD->siteRoot(); ?>/logout';});return false;">Logout</a>
						&nbsp;&nbsp;<a href="<? $POD->siteRoot(); ?>/facebook?rfb=1"  onclick="FB.Connect.logout(function(){window.location='<? $POD->siteRoot(); ?>/facebook?rfb=1';});return false;">Remove</a>
					</p>
				</div>
			<? } else { ?>
			
				<p>When you connect, you'll be able to login with your Facebook account, and automatically post your activity to your Facebook wall.</p>
			
				<p><fb:login-button v="2" size="medium" autologoutlink="true" onlogin="window.location='<? $POD->siteRoot(); ?>/facebook';">Connect with Facebook</fb:login-button></p>

			<? } ?>
			<? if ($POD->isAuthenticated()) { ?>
				<p class="right_align"><a href="/editprofile#connect">Return to Connect Preferences &#187;</a></p>
			<? } else { ?>
				<p><a href="<? $POD->siteRoot(); ?>/login">&larr; Return to Login</a></p>
			<? } ?>
				

				
	</div>