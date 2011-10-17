<?

/* Bug Browser class
	This class provides a basic framework for browsing bugs within the MediaBugs system.

	Functionality is added to this framework through plugins, found in the /browsers subfolder.
	
	See /browsers/README.txt


	Create a new browser:
	$B = new Browser($POD);
	
	Create a new browser with a default breadcrumb
	$B = new Browser($POD,array('<a href="#">Home</a>'));
	
	Get a list of all the browse modes:
	$modes = $B->modelist();
	
	Add to the breadcrumbs:
	$B->addCrumbs('<a href="#">Sub Option</a>');
	
	Print Crumbs:
	$B->crumbs('optional last crumb');
	
	Output an <li> object with browse "teasers" (for use on /bugs homepage)
	$B->browseStarters($mode);
	
	Output the "default" page for a browse mode (/bugs/browse/$mode)
	$B->browseDefault($mode,$sort,$offset);
	
	Output the actual bug results:
	$B->browseBy($mode,$keyword,$sort,$offset);
	
	Do the actual query and return a stack object
	$B->query($query,$sort,$offset)
	
	Display the results (to be called from within plugin only!)
	$B->results($query,$sort,$offset,$keyword);

	ben brown
	ben@xoxco.com
	2011-01-25


*/

class Browser {

	static private $modes = array();
	public $crumbs = array();
	public $POD;
	public $mode = null;
	
	function Browser($POD,$breadcrumbs=null) { 

		$this->POD = $POD;
		if ($breadcrumbs) {
			$this->crumbs = $breadcrumbs;
		}
		return $this;	
	}


	function modelist() {
	
		return self::$modes;
	}
	
	function crumbs($last=null) {
		if ($last) {
			$this->addCrumbs($last);
		}
		echo "<h1 id=\"browse_crumbs\">";
		echo implode(" / ",$this->crumbs);
		echo "</h1>";
	
	}
	
	function addCrumbs($crumb) {
		$this->crumbs[] = $crumb;
	}
	
	// display a short, simple list of places to start browsing
	function browseStarters($mode) { 
		$this->mode = $mode;

		$args = array();
		if (self::$modes[$mode]['starters']) {
			
			
			if (self::$modes[$mode]['headerfunction']) {
				 call_user_func_array(self::$modes[$mode]['headerfunction'],array($this));
			} 
		
			$title = self::$modes[$mode]['title'];
			
			$link = $this->POD->siteRoot(false) . '/bugs/browse/' . $mode;
			
			echo "<li>";
			echo "<a href=\"{$link}\">{$title}</a>";
			echo "<ul>";	

			$items = call_user_func_array(self::$modes[$mode]['starters'],array($this));
			foreach ($items as $query=>$title) { 
				if ($query !='') { 
					$query = "/?q={$query}";
				}
				echo "<li><a href=\"{$link}{$query}\">{$title}</a></li>";
			}
			echo "</ul>";
			echo '<div class="clearer"></div>';
			echo "</li>";
		} else {
			// do nothing
		}
	}
	

	// display all the ways to browse by this mode
	function browseDefault($mode,$sort,$offset) {
		$this->mode = $mode;
		if (self::$modes[$mode]['default']) {
			call_user_func_array(self::$modes[$mode]['default'],array($this,$sort,$offset));
		}
	}


	// display results for this term
	function browseBy($mode,$keyword,$sort,$offset,$return=false) { 
		$this->mode = $mode;
		if (self::$modes[$mode]['browseby']) {
			$query = call_user_func_array(self::$modes[$mode]['browseby'],array($this,$keyword,$sort,$offset));
			if ($return) {
				return $query;			
			} else {
				$this->results($query,$sort,$offset,$keyword);
			}
		}
	}	
	
	function results($query,$sort,$offset,$keyword=null) { 

		$docs = $this->query($query,$sort,$offset);
		$this->crumbs();
		if (self::$modes[$this->mode]['browseheaderfunction']) {
			call_user_func_array(self::$modes[$this->mode]['browseheaderfunction'],array($this));
		}
		$docs->output('short','browse.header','pager',null,'No bugs found that match your criteria',"&q=".urlencode($keyword) . "&sort={$sort}"); 

	}

	function query($query,$sort,$offset) {
	
		$POD = $this->POD;

		$sortBy ='';
		list($sort,$direction) = explode(":",$sort);
		if (!$direction) {
			$direction = "DESC";
		}

		if ($sort == "date") { 
			$sortBy = "d.date {$direction}";
		} else if ($sort=="modification") { 
			$sortBy = "d.changeDate {$direction}";
		} else if ($sort =="comments") { 
			// make sure we have a comment count variable
			$query['comment_count:!=']='null';
			// set it up to sort by the meta field
			$sortBy = "d_m_comment_count.value {$direction}";
		} else if ($sort =="views") { 
			// make sure we have a views value
			$query['views:!='] = 'null';
			$sortBy = "d_m_views.value {$direction}";
		}
		
		$query['type'] = 'bug';
		$query['!and'] = array('userId'=>$POD->anonymousAccount(),'status'=>'new');
		return $POD->getContents($query,$sortBy,10,$offset);

	}




	function addBrowseMethod($path,$starters,$title,$headerfunction=null,$default=null,$browseby,$browseheaderfunction=null) {
	
		self::$modes[$path]['starters'] = $starters;
		self::$modes[$path]['title'] = $title;
		self::$modes[$path]['headerfunction'] = $headerfunction;
		self::$modes[$path]['default'] = $default;
		self::$modes[$path]['browseby'] = $browseby;
		self::$modes[$path]['browseheaderfunction'] = $browseheaderfunction;
	
	}

}