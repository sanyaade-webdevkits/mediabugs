<form id="contact_form" method="post" class="valid" action="/contact">

	<? if ($user->contact_form_error) { ?>
		<div class="contact_form_error">
			<?= $user->contact_form_error; ?>
		</div>	
	<? } ?>

	<p class="input">
		<label>From:</label>
		<input type="text" name="from" class="text required email" value="<? $user->email; ?>" />
	</p>
	
	<p class="input">
		<textarea type="text" name="message" class="text required"><?= htmlspecialchars($user->contact_form_message); ?></textarea>
	</p>
	
	<p class="input">
		<input type="submit" value="Send Message" class="littlebutton" />
	</p>

	<div class="clearer"></div>
</form>