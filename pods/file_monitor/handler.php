<?

	require_once("../../PeoplePods.php");
	$POD = new PeoplePod();
	
	if ($POD->libOptions('file_monitor_email')) {
		$command = "/usr/bin/find " . $POD->libOptions('installDir') . ' -printf "%p\t%s\t%t\t%c\t%G\t%U\t%m\n" | grep -v "peoplepods/files" | grep -v "history/last.txt"';
		$latest = array();
		exec($command,$latest);
	
		if (file_exists("history/last.txt")) { 
		
			$last = file_get_contents("history/last.txt");
		}		
	
		$out = fopen("history/last.txt","w");	
		foreach ($latest as $line) {
			fwrite($out,"$line\n");
		}
		fclose($out);
		
		if ($last) { 
		
			$last = explode("\n",$last);
			
			$current = array();
			$history = array();
			
			foreach ($last as $line) { 
			
				$fields = explode("\t",$line);
				$history[$fields[0]] = array('size'=>$fields[1],'mod_time'=>$fields[2],'status_time'=>$fields[3],'group'=>$fields[4],'user'=>$fields[5],'permissions'=>$fields[6]);
			}
			foreach ($latest as $line) { 
			
				$fields = explode("\t",$line);
				$current[$fields[0]] = array('size'=>$fields[1],'mod_time'=>$fields[2],'status_time'=>$fields[3],'group'=>$fields[4],'user'=>$fields[5],'permissions'=>$fields[6]);
			}
			
			$report = '';
			
			
			foreach ($current as $name=>$vals) { 
				if (!$name) { continue; }
				
				
				$dname = preg_replace("|" . $POD->libOptions('installDir') . "|","",$name);
				
				
				if (!$history[$name]) { 
					$report .= "{$dname} was created!\n\n";
				} else {
					if ($vals['size'] != $history[$name]['size']) { 
						$report .= "{$dname} has changed size, to {$vals['size']} from {$history[$name]['size']}\n\n";
					}
					if ($vals['mod_time'] != $history[$name]['mod_time']) { 
						$report .= "{$dname} modified time has changed, to {$vals['mod_time']} from {$history[$name]['mod_time']}\n\n";
					}
	//				if ($vals['status_time'] != $history[$name]['status_time']) { 
	//					$report .= "{$dname} status time has changed, from {$vals['status_time']} to {$history[$name]['status_time']}\n\n";
	//				}
					if ($vals['group'] != $history[$name]['group']) { 
						$report .= "{$dname} group setting has changed, to {$vals['group']} from {$history[$name]['group']}\n\n";
					}
					if ($vals['user'] != $history[$name]['user']) { 
						$report .= "{$dname} has modified time has changed, to {$vals['user']} from {$history[$name]['user']}\n\n";
					}
					if ($vals['permissions'] != $history[$name]['permissions']) { 
						$report .= "{$dname} has modified time has changed, to {$vals['permissions']} from {$history[$name]['permissions']}\n\n";
					}			
				}
			
			}
			
			foreach ($history as $name=>$vals) {
				if (!$name) { continue; }
				$dname = preg_replace("|" . $POD->libOptions('installDir') . "|","",$name);
	
				if (!$current[$name]) { 
					$report .= "{$dname} was deleted!\n\n";
				}
			}
			
			if ($report != '') {
				mail($POD->libOptions('file_monitor_email'),'File changes detected on ' . $POD->siteName(false),$report);
			}
	} else {
		// no check to be done because no historic data.		
	}
	
			
	} else {
		echo "<p>Please set the file monitor email address in the PeoplePods control center.</p>";
		echo '<p>It should be located at <a href="' . $POD->siteRoot(false) . '/peoplepods/admin/options/podsettings.php?pod=file_monitor">' . $POD->siteRoot(false) . '/peoplepods/admin/options/podsettings.php?pod=file_monitor</a></p>';
	}		
