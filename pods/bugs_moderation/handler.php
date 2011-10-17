<?
	include_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('debug'=>0,'lockdown'=>'adminUser','authSecret'=>$_COOKIE['pp_auth']));
		
		$POD->header('Moderate Anonymous Bugs');
		?>
			<div class="column_8">
		
			<h3><A href="<? $POD->siteRoot(); ?>/peoplepods/admin">PeoplePods Command Center</a></h3>
	

			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/content/search.php?type=bug&userId=<?= $POD->anonymousAccount(); ?>">Moderate Anonymous Bugs</a></h3>
			<p>These bugs were submitted by someone without an account.  They will not appear in the browse section of the site
			until they are approved.</p>

			<h3><a href="<? $POD->siteRoot(); ?>/featured">Change order of featured bugs</a></h3>
		
			<h3><a href="<? $POD->siteRoot(); ?>/email_everyone">Send an email newsletter to all subscribers</a></h3>	
			
			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/flags/?flag=report&type=comment">Flagged Comments</a></h3>
			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/flags/?flag=report&type=user">Flagged People</a></h3>
			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/flags/?flag=report&type=content">Flagged Bugs</a></h3>


			<h3><a href="<? $POD->siteRoot(); ?>/outlets">Media Outlets</a></h3>
			<p>New media outlets will not appear in the browse interface until they are approved.</p>
		
			
			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/content/search.php?type=bug">All Bugs</a></h3>
			<p>View all recent bugs</p>			

			<h3><a href="<? $POD->siteRoot(); ?>/peoplepods/admin/comments/">Recent Comments</a></h3>
			<p>View recent comments</p>
			</div>
		<?
		$POD->footer();	





?>
