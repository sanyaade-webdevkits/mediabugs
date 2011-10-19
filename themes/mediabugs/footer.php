<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* theme/footer.php
* Defines what is in the footer of every page, used by $POD->footer()
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme/themes
/**********************************************/
?>

		<div class="clearer"></div>
	</div> <!-- main -->
	<div id="footer">
		<div class="grid">
			<ul>
				<li>
					<a href="<?php $POD->siteRoot() ?>">Home</a>
				</li>
				<li>
					<a href="<?php $POD->siteRoot() ?>/bugs/edit">Report a Bug</a>
				</li>
				<li>
					<a href="<?php $POD->siteRoot() ?>/bugs">Browse Bugs</a>
				</li>
				<? if ($POD->isAuthenticated() && $POD->currentUser()->adminUser) { ?>
					<li>
						<a href="<? $POD->siteRoot(); ?>/moderation">Admin tools</a>
					</li>			
				<? } ?>
			</ul>
		</div>
	</div>	
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-15426486-1");
	pageTracker._setDomainName(".mediabugs.org");
	pageTracker._trackPageview();
	} catch(err) {}</script>


</body>
</html>