<? 

	$POD->registerPOD('bugs_authentication_login','Allows Login, Logout, Password Reset',array('^r/(.*)'=>'bugs_authentication/login.php?checklogin=1&redirect=/$1','^login'=>'bugs_authentication/login.php','^logout'=>'bugs_authentication/logout.php','^password_reset/(.*)'=>'bugs_authentication/password.php?resetCode=$1','^password_reset$'=>'bugs_authentication/password.php'),array());
	$POD->registerPOD('bugs_authentication_creation','Allows new members to join your site',array('^join'=>'bugs_authentication/join.php','^verify'=>'bugs_authentication/verify.php'),array());

?>