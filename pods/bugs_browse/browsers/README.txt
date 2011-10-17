The MediaBugs bug browser is extensible through simple browser plugins.
Each plugin defines a new browse "mode," which allows the bug database
to be explored in different ways.

To create a new plugin, create a new file PHP in within the pods/bugs_browse/browsers folder.
It doesn't matter what it is called, though the plugins are loaded in alphabetical order,
so if you want them to present on the browse homepage in a specific order, I recommend
naming your file something like 01_modename.php

The functionality created in the plugin files is used by the Browser class.  Each function
takes a Browser object as its first parameter, and you may call Browser methods from
within the handler functions (such as addCrumbs, crumbs, etc)

Inside this new PHP function, you must define 3 handler functions for the various pieces of the browser.

1. The "teaser" or "starters" function for the Browser homepage at /bugs.

This function should return an array of query=>label pairs that will be used to generate a
directory-style list on the /bugs homepage.  For example, a list of bug types.

2. The "default" function for the /bugs/browse/$mode page.

This function should output a more extensive directory of potential browse options.
For example, a list of every possible bug type.

In the event that you want to simply redisplay the starters from the homepage,
call $browser->browseStarters($mode), as in the example below.


3. The "browseBy" function, for /bugs/browse/$mode?q=$query

This function specifies the query options for the actual bug results, and adds any crumbs.

Finally, tell the Browser class that a new method is available 
and specify how to display the method and generate various views by calling the 
Browser::addBrowseMethod function, as below.


Browser::addBrowseMethod(
	$mode,				// used to identify this mode, build urls
	$starters_function,	// returns a short list of potential browse categories for this mode displayed on /bugs=
	$title,				// title for this browse mode
	$header_function,	// OPTIONAL, adds something special above the title (a map, perhaps?)
	$default_function,	// displays the default page for this mode.  /bugs/browse/$mode
	$browse_function,	// prepares the query and calls $browser->results
);



Browser::addBrowseMethod('type','type_starters','Browse by Type',null,'type_default','type_browseBy');

function type_starters($browser) {

	$bug_types = $browser->POD->bugTypes();

	$ret = array();
	foreach ($bug_types as $type) { 
		$ret[$type->headline] = $type->headline;
	}
	return $ret;
}


function type_default($browser,$sort,$offset) {

	$browser->crumbs('Bug Types');
	echo "<ul class=\"directory\">";
	$browser->browseStarters('type');
	echo "</ul>";
}

function type_browseBy($browser,$keyword,$sort,$offset) { 

	$browser->addCrumbs('<a href="' . $browser->POD->siteRoot(false).'/bugs/browse/type">Bug Types</a>');
	$browser->addCrumbs($keyword);
	
	return array('bug_type'=>$keyword,'bug_status:!='=>'closed:off topic');

}