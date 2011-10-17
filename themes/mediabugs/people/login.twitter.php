	<div id="connect_form" class="round_white_box">

			
			<h1>Connect to Twitter</h1>
			
			<? if ($user->get('twitter_name')) { ?>
				<div class="connect_details">
						<p><strong>Twitter Name:</strong> <a href="http://twitter.com/<? $user->write('twitter_name'); ?>"><? $user->write('twitter_name'); ?></a>&nbsp;&nbsp;<a href="<? $POD->siteRoot(); ?>/twitter?remove=1" >Remove</a></p>
				</div>
			<? } else { ?>
			
			<p>When you connect, you'll be able to login with your Twitter account, and automatically post your activity to your Twitter stream.</p>
			
			<a href="<? $POD->siteRoot(); ?>/twitter/verify" class="littlebutton">Login to Twitter</a>
			
			<? } ?>

			<? if ($POD->isAuthenticated()) { ?>
				<p class="right_align"><a href="<? $POD->siteRoot(); ?>/editprofile#connect">Return to Connect Preferences &#187;</a></p>
			<? } else { ?>
				<p><a href="<? $POD->siteRoot(); ?>/login">&larr; Return to Login</a></p>
			<? } ?>
				
	</div>