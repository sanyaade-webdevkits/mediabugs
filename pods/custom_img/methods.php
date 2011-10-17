<?


function customImg($content,$field) {

	$POD = $content->POD;
	
	$value = $content->get($field);

	$value = preg_replace_callback("/\[img (.*?)\]/ism",array(&$content,"customImgMakeImg"),$value);

	return $value;


}

function customImgMakeImg($content,$tags) { 
	$str = '';
		$parameters = $tags[1];
		preg_match_all("/(.*?)=\"(.*?)\"\s*/",$parameters,$matches,PREG_SET_ORDER);
		foreach ($matches as $match) { 
			$options[trim($match[1])] = $match[2];		
		}
	
		if ($options['name']) {
			if ($file = $content->files()->contains('file_name',$options['name'])) { 
				if ($options['type']) { 
					ob_start();
					$file->output($options['type']);
					$str = ob_get_contents();
					ob_end_clean();
				} else {
					$str = "<strong>[BAD IMG TAG! No type!]</strong>";
				}
			} else {
				// can't find file
				$str = "<strong>[BAD IMG TAG!  Could not find file]</strong>";
			}
		} else { 
			// no name param
			$str = '<strong>[BAD IMG TAG! No file name!]</strong>';
		}
	return $str;

}



Content::registerMethod('customImg');

Content::registerMethod('customImgMakeImg');
?>
