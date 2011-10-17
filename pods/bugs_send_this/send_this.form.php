<form method="post" action="/send" id="send_this" class="valid">
	<input type="hidden" name="id" value="<?= $doc->id; ?>" />

	<? if ($doc->contact_form_error) { ?>
		<div class="send_this_error">
			<?= $doc->send_this_error; ?>
		</div>	
	<? } ?>

	<? $doc->output('short'); ?>
	
	<h3>E-mail This</h3>

	<p class="input">
		<label for="email">Recipient's E-mail:</label>
		<input type="text" name="email" id="email" class="text required email" value="<?= htmlspecialchars($doc->send_this_recipient); ?>" />
	</p>
	
	<p class="input">
		<label for="message">Message</label>
		<textarea name="message" id="message" class="text required"><?= htmlspecialchars($doc->send_this_message); ?></textarea></textarea>
	</p>

	<p class="input">
		<input type="submit" value="Send e-mail" class="littlebutton" />
	</p>
	
	<div class="clearer"></div>

</form>