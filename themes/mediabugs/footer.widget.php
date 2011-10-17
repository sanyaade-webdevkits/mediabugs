<script type="text/javascript">
	
		$().ready(function(){ 
		
			$('form.valid').submit(function(){ 
				if (tinyMCE) { 
					tinyMCE.triggerSave();
				}			
			})
		
			$('form.valid').validate({
				
			
			});

			$('textarea.tinymce').tinymce({
				script_url : podRoot+'/themes/admin/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
				theme: "advanced",
				width: "98%",
				height:"60",
				valid_elements: "p,blockquote,h1,h2,h3,h4,h5,h6,ol,ul,li,br,em,strong,i,u,b,strike,a[href|target|title],img[src|width|height|alt|border|title]",
				plugins:"paste",
				paste_auto_cleanup_on_paste: true,
				paste_strip_class_attributes: "all",
				paste_remove_spans: true,
				paste_remove_styles: true,
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",	
				theme_advanced_buttons1: "bold,italic,separator,bullist,numlist,separator,link,unlink,separator,undo,separator,charmap",
				theme_advanced_buttons2: "",
			});


		});
	</script>

</body>
</html>