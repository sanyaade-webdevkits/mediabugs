<?

	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('lockdown'=>'adminUser','debug'=>0,'authSecret'=>$_COOKIE['pp_auth']));


	if ($_POST) { 
	
		if ($_POST['send_test']==1) { 
		
			$people = $POD->getPeople(array('id'=>$POD->currentUser()->id));
		} else {
			$people = $POD->getPeople(array('newsletter'=>1),null,10000000);			
		}

		$count = 0;	
		foreach ($people as $person) { 
			$person->sendEmail('email_everyone',$_POST);
			$count++;
		}
		
		$msg = "Message sent to $count people";

	} 
	
	
	
	
	$POD->header('Email Everyone');
	
	if ($msg) { ?>
		<div class="info">
			<p><?= $msg; ?></p>
		</div>
	<? } ?>
	
		<div class="column_6">
		<form method="post" action="/email_everyone" class="valid">
		
		
			<h1>Email Everyone</h1>		

			
			<p class="input">
				<label>Subject:</label>
				<input type="text" name="subject" class="text required" value="<?= htmlspecialchars(stripslashes($_POST['subject'])); ?>" />
			</p>

			<p class="input">
				<label>Message:</label>
				<textarea name="message" class="text required"><?= htmlspecialchars(stripslashes($_POST['message'])); ?></textarea>			
			</p>		
			
			<p class="input">
				<input type="checkbox" name="send_test" value="1" checked /> Send test only
			</p>
			
			<p>
				<input type="submit" value="Send" />
			</p>
		
		
		
		</form>

		</div>
	
	<?
	
	$POD->footer();