MediaBugs: Single Publication Version

The MediaBugs Single Publication Version allows you to host your a bug reporting platform for your media outlet.

Requirements:
PHP 5+
MySQL 4+

Install Procedure:
1. Create a MySQL database for MediaBugs.
2. At the command line, navigate into the directory from which you'd like to serve MediaBugs: either in your site root, or in a subdirectory. In this directory, create an .htaccess file and make sure it's writeable by the web user. The 'peoplepods' directory, where all the MediaBugs functionality will live, will be created into this directory during the next step.
3. Clone MediaBugs into the 'peoplepods' directory. You can do this by typing:

  git clone git@github.com:xoxco/mediabugs.git peoplepods

at the command line. Alternative, download the tarball from

  https://github.com/xoxco/mediabugs/tarball/master 

into your chosen directory, and rename the directory that was created to "peoplepods".
4. Once you have cloned MediaBugs, make sure the peoplepods directory is writeable by the web user.
5. Navigate to http://SITE_ROOT/peoplepods to begin the installation process. Proceed through the steps and provide the requested-for information.
6. Once the "MediaBugs onetime setup" has run, go to the MediaBugs publication admin tool at http://SITE_ROOT/spvadmin. Enter a name and URL for your media outlet, and provide an image if you have one. 

How to enable Facebook integration:
1. Go to https://developers.facebook.com/apps and click "Create New App". Name the app whatever you'd like. You don't need to provide a namespace.
2. Under the "Basic Info" section, for "App Domain" enter your MediaBugs site's domain.
3. Under "Select how your app integrates with Facebook", click "Website". In the "Site URL" box, enter the URL of your website.
4. Take note of your "App ID" at the top of the page. Navigate to http://SITE_ROOT/spvadmin, and enter your App ID into the "Facebook App ID". Once this is set, visitors will be able to post bugs and comments and you can be assured that they are using their real names.

How to enable Akismet spam prevention:
1. Get an Akismet API key by signing up at http://www.akismet.com. Once you've signed up, they will send you an email with your API key.
2. Once you have an Akismet API key, go to the peoplepods admin tool at http:///peoplepods/admin/options/pods.php and make sure the 'akismet' pod is enabled (it will be enabled by default). In the 'akismet' row, hit 'settings' and enter your API key on the next page.
3. Test the spam-catching system by trying to post a bug with a spammy title/content - your post should be rejected.