<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/people/join.php
* Used by the core_authentication pod to create the /join page
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/person-object
/**********************************************/

	$needs_password = true;
	
	
?>

	<div id="connect_form">
		<form method="post" id="join" action="<? $POD->siteRoot() ?>/join" class="valid">

			<input type="hidden" name="redirect" value="<? echo htmlspecialchars($user->get('redirect')); ?>" />
			<input type="hidden" name="code" value="<? echo htmlspecialchars($user->get('code')); ?>" />			
			<h1>Welcome to <? $POD->siteName(); ?>!</h1>

			<? if ($user->get('invited_by')) { ?>
				<div class="info">
					<P>You were invited to join this site by <? $user->get('invited_by')->permalink(); ?>.
				
					<? if ($user->get('invited_to_group')) { ?>
						<p><? $user->get('invited_by')->write('nick'); ?> wants you to join the group <? $user->get('invited_to_group')->permalink(); ?>.</p>	
					<? } ?>
				</div>
			<? } ?>

			<? if (!$user->get('openid')  && !$user->get('fbuid')) { ?>
			<p>Login with <a href="<? $POD->siteRoot(); ?>/openid">OpenID</a> or <a href="<? $POD->siteRoot(); ?>/facebook">Facebook</a></p>
			<? } ?>

			<? if (isset($_COOKIE['claim'])) { 
				$bug = $POD->getContent(array('id'=>$_COOKIE['claim']));
				if ($bug->success()) { ?>
					<div class="dialog alert">
						<p>Once you've joined,
						we'll automatically add the bug you just submitted, "<?= $bug->headline; ?>",
						to your account.</p>
					</div>
				<? }
			} ?>
			


			<p class="input">
				<label for="name">Your Name:</label>
				<input class="required text" name="name" value="<? $user->htmlspecialwrite('nick'); ?>" maxlength="40"/>
			</p>
			
			<p class="input">
				<label for="email">Your Email:</label>
				<input class="required validate-email text" value="<? $user->htmlspecialwrite('email'); ?>" name="email" />
			</p class="input">
			
			<? if ($user->get('fbuid')) { $needs_password = false; ?>
				<p><label>Facebook:</label> Connected!</p>
				<input type="hidden" name="meta_fbuid" value="<?= $user->write('fbuid'); ?>" />
			<? } ?>
			<? if ($user->get('twitter_name')) { $needs_password = false; ?>
				<p><label>Twitter:</label> <a href="http://twitter.com/<? $user->write('twitter_name'); ?>"><? $user->write('twitter_name'); ?></a> Connected!</p>
				<input type="hidden" name="meta_twitter_token" value="<?= $user->write('twitter_token'); ?>" />
				<input type="hidden" name="meta_twitter_secret" value="<?= $user->write('twitter_secret'); ?>" />
				<input type="hidden" name="meta_twitter_name" value="<?= $user->write('twitter_name'); ?>" />
				<input type="hidden" name="meta_twitter_id" value="<?= $user->write('twitter_id'); ?>" />

			<? } ?>

			<? if ($user->get('openid')) { $needs_password = false;?>
				<p>
					<label for="password">OpenID:</label>&nbsp;<?= $user->write('openid'); ?>
					<input type="hidden" name="meta_openid" value="<?= $user->write('openid'); ?>"/>
				</p>	
			<? } ?>
	
			
			<? if ($needs_password){?>
			
			<p class="input">
				<label for="password">Choose a Password:</label>
				<input class="required text" name="password" type="password" />
			</p>
			<? } ?>

		<p class="input">
				<label>&nbsp;</label>
				<input name="meta_is_journalist" value="1" type="checkbox" /> I am a journalist.  <a href="#" onclick="return false;" class="glossary" title="We consider you a journalist if you are employed by a media outlet to cover news, or if you regularly provide news reports to any community." /><i>What do we mean by "journalist"?</a> And <a href="#" onclick="return false;" class="glossary" title="So we can see how well MediaBugs is serving both journalists and non-journalists." />why are we asking?</i></a>
			</p>


			<p class="input">
				<input type="submit"  class="button with_right_margin" value="Create my account" name="create" />By clicking "Create" you agree to our <a href="<? $POD->siteRoot(); ?>/pages/tos">Terms of Service</a>
			</p>
		
			<p class="form_text"></p>
		</form>
		<p class="right_align">Already have an account?  <a href="<? $POD->siteRoot(); ?>/login">Login here</a></p>
	</div>