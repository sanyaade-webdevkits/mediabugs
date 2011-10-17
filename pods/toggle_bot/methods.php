<?

	function toggleBot($POD,$bool,$html_id,$on_message,$off_message,$api_call,$js_handler=null,$extra_classes=null,$on_title=null,$off_title=null) { 
		$html = '';
		
		$text = $bool ? $on_message : $off_message;
		$active = $bool ? 'active' : '';
		if (!$on_title) { $on_title = $on_message; }
		if (!$off_title) { $off_title = $off_message; }

		$title = $bool ? $on_title : $off_title;
		if (!isset($js_handler)) {
			$js_handler = 'null';
		}

		$html = "<a href=\"#\" id=\"{$html_id}\" title=\"" . htmlspecialchars($title) . "\" class=\"toggleBot {$html_id} {$active} {$extra_classes}\" onclick=\"return toggleBot('{$html_id}','" . htmlspecialchars($on_message) ."','".htmlspecialchars($off_message) ."','{$api_call}',{$js_handler});\">{$text}</a>";
		
		return $html;
	}
	
	PeoplePod::registerMethod('toggleBot');	

?>