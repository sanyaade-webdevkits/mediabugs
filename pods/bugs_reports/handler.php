<?

	require_once("../../PeoplePods.php");
	$POD = new PeoplePod(array('authSecret'=>$_COOKIE['pp_auth'],'lockdown'=>'adminUser'));
	
	$mode = isset($_GET['mode']) ? $_GET['mode'] : 'menu';
	
	if ($mode=='menu') { 
		$POD->header('Reports');	
		
		?>
	
		<h1>Reports</h1>
		
		
		<p><a href="/reports/allbugs">All bugs</a></p>
		<p><a href="/reports/closed">Closed bugs with survey responses</a></p>
	
	
	
		<?
		$POD->footer();
	} else if ($mode=='allbugs') { 


		header('Content-type: application/csv');
		header("Content-Disposition: inline; filename=allbugs." . date('Y-m-d') . '.csv');
	
		echo "Title,Type,Status,Media Outlet,Date Reported,Most Recent Change,Reported By\n";
	
		$bugs = $POD->getContents(array('type'=>'bug'),'date DESC',1000000);
		foreach ($bugs as $bug) { 
		
			// bug
			// bug type
			// status

			// reported
			// last modified
			// outlet
			// author

			$outlet = $POD->getContent(array('id'=>$bug->bug_target));
			
			$row = array();
			$row['title'] = commaprep($bug->headline);
			$row['type'] = commaprep($bug->bug_type);
			$row['status'] = commaprep($bug->bug_status);
			$row['media_outlet'] = commaprep($outlet->headline);
			$row['date_reported'] = $bug->date;
			$row['date_modified'] = $bug->changeDate;
			$row['reported_by'] = commaprep($bug->author()->nick);
		
			echo implode(",",$row) . "\n";
		
		
		}
	} else if ($mode=='closed') { 
	

		header('Content-type: application/csv');
		header("Content-Disposition: inline; filename=survey." . date('Y-m-d') . '.csv');
	
		echo "Title,Type,Status,Media Outlet,Date Reported,Most Recent Change,Reported By,Satisfied with outcome?,Satisfied with outlet response?,Trust media outlet?,Survey comments\n";
	
		$bugs = $POD->getContents(array('type'=>'bug','bug_status:like'=>'closed%'),'date DESC',1000000);
		foreach ($bugs as $bug) { 
		
			// bug
			// bug type
			// status

			// reported
			// last modified
			// outlet
			// author

			$outlet = $POD->getContent(array('id'=>$bug->bug_target));
			
			$row = array();
			$row['title'] = commaprep($bug->headline);
			$row['type'] = commaprep($bug->bug_type);
			$row['status'] = commaprep($bug->bug_status);
			$row['media_outlet'] = commaprep($outlet->headline);
			$row['date_reported'] = $bug->date;
			$row['date_modified'] = $bug->changeDate;
			$row['reported_by'] = commaprep($bug->author()->nick);
			
			$row['Satisfied with Outcome?'] = $bug->outcome_survey;
			$row['Satisfied with outlet response?'] = $bug->response_survey;
			$row['Trust media outlet?'] = $bug-> trust_survey;
			$row['Survey comments'] = $bug->survey_comments;
	
			echo implode(",",$row) . "\n";
		
		
		}	
	}


	function commaprep($str) { 
		$str = '"' . preg_replace('/\"/','""',$str) . '"';
		return $str;
		
	}

?>