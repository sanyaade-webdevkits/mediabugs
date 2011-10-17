<head>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="<? $POD->templateDir(); ?>/js/jquery.validate.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<? $POD->templateDir(); ?>/js/jquery-autocomplete/jquery.autocomplete.css" media="screen" charset="utf-8" />

	<style>

		body { width: 600px; background: #FFF; padding:0px; margin:0px; font-family: Georgia;} 		
		h1 { font-weight: normal; font-size: 16px; margin-top:0px; padding:10px; padding-top:20px; font-family: arial; background: #B6DAAE; min-height:50px; }
		#left { width: 270px; float: left; padding:10px; padding-left:20px; }
		#right { width: 270px; margin-left: 300px;padding:10px; padding-right:20px;}
		p { font-size:14px; } 
		
		#next { list-style-type: none; padding:0px; margin:0px; }
		#next li { display: block; margin-bottom: 10px; font-family: arial;} 
		#next l a { font-weight: bold; font-family: arial; }
		#next li input { width: 100%; }
		
		
		p.input { margin-top: 0px; padding-top: 0px; }
		p.input label { font-weight: bold;display: block; font-size:16px; }
		p.input input.text { width: 250px;  font-size: 18px; border: 1px solid #ccc;  padding:5px;} 
		p.input select.text { width: 250px;  font-size: 18px; } 
		p.input textarea.text { width: 250px; height: 50px; border: 1px solid #ccc; font-size: 18px; margin-top: 0px;background: url(<? $POD->templateDir(); ?>/img/input_skin.png) no-repeat;}  
		.button { display: block; float: left; font-family: helvetica; font-weight: bold; letter-spacing: -1px; font-size: 16px; line-height: 16px; padding-top: 19px; padding-bottom:19px; background: url(<? $POD->templateDir(); ?>/img/button_skin.png); padding-left: 30px; padding-right: 30px;  color: #FFF; text-shadow: #333 1px 1px 1px; border: 1px solid #336633; -moz-border-radius: 12px; -webkit-border-radius: 12px; text-align: center; margin-right: 20px;}
		.littlebutton { display: block; float: left; font-family: helvetica; font-weight: bold; letter-spacing: -1px; font-size: 16px; line-height: 16px; padding-top: 7px; padding-bottom:7px; background: url(<? $POD->templateDir(); ?>/img/button_skin_small.png); padding-left: 10px; padding-right: 10px;  color: #FFF; text-shadow: #333 1px 1px 1px; border: 1px solid #336633; -moz-border-radius: 12px; -webkit-border-radius: 12px; text-align: center; margin-right: 20px; }
		p.input input.error { border-color:1px solid #F00;}
		p.input label.error { font-size: 12px; color: #f00; } 
		.clearer { float: none; height: 0px; width: 100%; clear: both; }
	</style>
	
	<script>
		var podRoot = '<? $POD->podRoot(); ?>';
	</script>

</head>
<body>