	var WATCH_ON;
	var WATCH_OFF;
	var WATCH_LINK;
	var FLAG_ON;
	var FLAG_OFF;
	var FLAG_LINK;
	var FLAG_COUNTER;
	var FLAG_SINGULAR;
	var FLAG_PLURAL;
	var SUB_ON;
	var SUB_OFF;
	var SUB_LINK;
	var FILE_COUNTER = 1;

	var new_bug_mode = false;


	$().ready( function() { 
	
		$('#nav_browse area').mouseover(function(e) { 
			$('#nav_browse').addClass('mapping');
		});	

		$('#nav_browse area').mouseout(function(e) { 
			$('#nav_browse').removeClass('mapping');
		});			
		
		
		$('#post_output img').jcaption({copyStyle:true,copyFloatToClass:false});
		
		url = window.location.pathname;
		var tab = 'home';
		if (url.indexOf('bugs/edit') > -1) {
			tab = 'report';
		} else if (url.indexOf('bugs') > -1) { 
			tab = 'browse';
		} else if (url.indexOf('dashboard') > -1) { 
			tab = 'my';
		} else if (url.indexOf('pages/help') > -1) { 
			tab='help';
		} else if (url.indexOf('pages/about') > -1) { 
			tab='about';
		} else if (url.indexOf('pages/contact') > -1) { 
			tab='contact';
		} else if (url.indexOf('pages') > -1) { 
			tab='about';
		}
		$('#nav_'+tab).addClass('active');
	
	
	
		$('form.valid').validate();
		
		if( !((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) ||  (navigator.userAgent.match(/iPad/i)))) { 
		
			$('textarea.tinymce').tinymce({
				script_url : podRoot+'/themes/admin/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
				theme: "advanced",
				width: "100%",
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
				content_css : podRoot+"/themes/mediabugs/tinymce.css"
			});
		}
		$('a.glossary').bt( {
			positions: ['right'],
		  fill: 'rgba(51, 204, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#FFF', fontWeight: 'bold'}			
			}
		);
		$('p.input input').bt( {
			 trigger: ['focus', 'blur'],
			positions: ['right'],
		  fill: 'rgba(51, 204, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#FFF', fontWeight: 'bold'}
			}
		);
		$('p.input textarea').bt( {
			 trigger: ['focus', 'blur'],
			positions: ['right'],
		  fill: 'rgba(51, 204, 0, .8)',
		   shadow: true,
		    shadowOffsetX: 3,
		    shadowOffsetY: 3,
		    shadowBlur: 8,
		    shadowColor: 'rgba(0,0,0,.6)',
		    shadowOverlap: false,	
		    padding:10,
			 cssStyles: {color: '#FFF', fontWeight: 'bold'}
			}
		);


	});


	function markAsRead(id) {		
		$.getJSON(API+"?method=markAsRead&docId="+id);
	}

	function showComments(id) {
		markAsRead(id); 
		$('.recent_summary').show();
		$('.recent_comments').hide();
		$('#comments_'+id).show();
		$('#link_'+id).hide();
		return false;
	}

	function submitBug() { 
	
		if (!validateSection('report')) { 
			showFileSection('report');
			return false;
		} else {
			$('#tab_report').addClass('completed');	
		}
		if (!validateSection('what')) { 
			showFileSection('what');
			return false;
		} else {
			$('#tab_what').addClass('completed');	
		}
		if (!validateSection('why')) { 
			showFileSection('why');
			return false;
		} else {
			$('#tab_why').addClass('completed');	
		}
		if (!validateSection('status')) { 
			showFileSection('status');
			return false;
		} else {
			$('#tab_status').addClass('completed');	
		}
		
		$('#save_button').hide();
		$('#saving_progress').show();
		return true;		
	}

	
		function validateSurvey() { 
			if (typeof(eval(window)['tinyMCE']) != 'undefined') { 
				tinyMCE.triggerSave();
			}
			return ($('#bug').validate().element('#oss') && $('#bug').validate().element('#rss') && $('#bug').validate().element('#tsm') && $('#bug').validate().element('#oss') && $('#bug').validate().element('#survey_comments'));
		}
	
		function validateSection(sectionName) { 

			if (typeof(eval(window)['tinyMCE']) != 'undefined') { 
				tinyMCE.triggerSave();
			}
			if (sectionName=='report') { 
				if ($('#bug').validate().element('#headline') && $('#bug').validate().element('#media_outlet_q')) {
					if ($('#media_outlet_new').css('display')=='block') {
						if ($('#new_media_outlet_print').attr('checked')||$('#new_media_outlet_tv').attr('checked')||$('#new_media_outlet_radio').attr('checked')||$('#new_media_outlet_online').attr('checked')) {
							return true;
						} else {
							// display custom error
							return false;
						}
					} else {
						return true;
					}
				}
				return false;
			
			}
			if (sectionName=='what') { 
			
					if ($('#bug').validate().element('#bug_type') && $('#bug').validate().element('#bug_body') && $('#bug').validate().element('#report_date')) {
						return true;
					} else {
						return false;
					}
			
			}		
			return true;
		}

		function tabClick(sectionName) {
		
			if ($('#tab_'+sectionName).hasClass('completed')) { 
				return showFileSection(sectionName);
			} else {
				return false;
			}
		}
	
		function nextSection(currentSection,sectionName) { 
		
			if (validateSection(currentSection)) { 
				$('#tab_'+currentSection).addClass('completed');
				showFileSection(sectionName);
			}
		
		}
	
		function showFileSection(sectionName) { 
			sections = ['report','what','why','status'];
		
			passed = false;
				
			for (var x in sections) {			
				section = sections[x];	
				if (section == sectionName) { 
					if ($('#tab_'+section).hasClass('completed')) { 
						$('#tab_'+section).removeClass('complete');
						$('#tab_'+section).addClass('revisited');
					} else {
						$('#tab_'+section).removeClass('complete');
						$('#tab_'+section).addClass('active');					
					}
					passed = true;
				} else {
					$('#tab_'+section).removeClass('active');
					$('#tab_'+section).removeClass('revisited');

					if ($('#tab_'+section).hasClass('completed')) { 
						$('#tab_'+section).addClass('complete');
					}
				}	
				
			}			
			if (!$('#bug_id').val()) {
				$('fieldset').hide();
				$('#'+sectionName).show();
			}
		}
	
		function removeFile(contentId,fileId) { 

			command = API + "?method=removeFile&contentId="+contentId+"&fileId="+fileId;
			$.getJSON(command,refreshFiles);
					
			return false;
		
		}
		
		
		function refreshFiles(json) {
		
			if (json.error) {	
				alert(json.error);
			} else {
				
				if (json.html) { 
					
					$('#files').html(json.html);
				}
			}
		
		
		
		}


		function addFile() { 
			file = document.createElement('input');
			file.setAttribute('type','file');
			file.setAttribute('name','file'+FILE_COUNTER);
			file.setAttribute('class','file');
			file.setAttribute('id','file'+FILE_COUNTER);
			document.getElementById('files').appendChild(file);
			FILE_COUNTER++;
			return false;
		}


		function repairField(obj,val) {
			obj = $(obj);
			
			if (obj.val()==val) { 
				obj.val('');
				obj.css("color","#000000");
			}  else if (obj.val()=='') {
				obj.val(val);
				obj.css("color","#336633");
			}
		
		}


		function getComments(doc) { 
			var command = API + "?method=getComments&docId=" + doc;
		//	comment_ajax=new Ajax.PeriodicalUpdater('comments',command,{method:'get',frequency:3,decay:1});		
			return false;				
		}
		
		
		function toggleCheckboxSub(id,obj) {
		
			var command = API+"?method=toggleSub&contentId="+id;
			$.getJSON(command,subCheckboxSuccess);
			SUB_LINK = obj;
			return false;
		
		}
		
		function subCheckboxSuccess(json) {
			if (json.error) {
				alert(json.error);
			} 
			
			if (json.isOn) {
				$('#'+json.html_id).attr('checked',true);
			} else {
				$('#'+json.html_id).attr('checked',false);
			}
			SUB_LINK = null;
		}		
		
		
		
		function toggleBot(html_id,on,off,api_parameters,handler) { 

			var command = API + '?' + api_parameters + '&html_id='+escape(html_id)+'&on='+escape(on)+'&off='+off;

			if(!handler) { handler = toggleBotSuccess; }

			$.getJSON(command,handler);

			return false;
		}
		
		function toggleBotSuccess(json) {
		
			if (json.error) {
				alert(json.error);
			}
			if (json.isOn) {
				$('#'+json.html_id).addClass('active');
				$('#'+json.html_id).html(json.on);
			} else {
				$('#'+json.html_id).removeClass('active');
				$('#'+json.html_id).html(json.off);				
			}
		
		}
		
	
		function metoocounter(json) { 
			toggleBotSuccess(json);
			if (json.count) { 
				$('#metoo_counter').html(json.count + pluralize(json.count,' person thinks this is a bug.',' people think this is a bug.'));
			}		
		}
	
		function multiToggleSuccess(json) { 
			if (json.error) {
				alert(json.error);
			}
			if (json.isOn) {
				$('.'+json.html_id).addClass('active');
				$('.'+json.html_id).html(json.on);
			} else {
				$('.'+json.html_id).removeClass('active');
				$('.'+json.html_id).html(json.off);				
			}
		
		}
	
		function pluralize(num,sing,plur) {
			if (num==1) { return sing; } else { return plur; }
		}
	
		function toggleBrowseSub(keyword,type,off_message,on_message,obj) {
			var command = API+"?method=toggleSub&keyword="+escape(keyword) + "&type="+escape(type);
			$.getJSON(command,subSuccess);
			SUB_ON = on_message;
			SUB_OFF = off_message;
			SUB_LINK = obj;
			
			return false;
		}
		
		function subSuccess(json) {
			if (json.error) {
				alert(json.error);
			} 
			
			if (json.subscribed) {
				$(SUB_LINK).html(SUB_ON);
			} else {
				$(SUB_LINK).html(SUB_OFF);
			}
			
			SUB_LINK = null;
			
		
		}
		function toggleUserFlag(id,flag,off_message,on_message,obj) {
			var command = API + "?method=toggleUserFlag&user="+id +"&flag=" + flag;
			$.getJSON(command,flagUserSuccess);
			
			FLAG_ON = on_message;
			FLAG_OFF = off_message;
			FLAG_LINK = obj;
			return false;
		}		

		function toggleCommentFlag(id,flag,off_message,on_message,obj) {
			var command = API + "?method=toggleCommentFlag&user="+id +"&flag=" + flag;
			$.getJSON(command,flagCommentSuccess);
			
			FLAG_ON = on_message;
			FLAG_OFF = off_message;
			FLAG_LINK = obj;
			return false;
		}		

		
		function flagUserSuccess(json) { 
		
			if (json.error) {
				alert(json.error);
			}
		
			if (json.flagged) { 
				$(FLAG_LINK).html(FLAG_ON);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_ON);
			} else {
				$(FLAG_LINK).html(FLAG_OFF);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_OFF);
			}		
			FLAG_LINK = null;
			if (json.count && FLAG_COUNTER) { 
				if (json.count == 1) { 
					$(FLAG_COUNTER).html(json.count + " " + FLAG_SINGULAR);
				} else {
					$(FLAG_COUNTER).html(json.count + " " + FLAG_PLURAL);
				}			
				FLAG_COUNTER = null;
			}
		}		
		

		function flagUserSuccess(json) { 
		
			if (json.error) {
				alert(json.error);
			}
		
			if (json.flagged) { 
				$(FLAG_LINK).html(FLAG_ON);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_ON);
			} else {
				$(FLAG_LINK).html(FLAG_OFF);
				$('a.' + json.flag + '_flag_'+json.user.id).html(FLAG_OFF);
			}		
			FLAG_LINK = null;
			if (json.count && FLAG_COUNTER) { 
				if (json.count == 1) { 
					$(FLAG_COUNTER).html(json.count + " " + FLAG_SINGULAR);
				} else {
					$(FLAG_COUNTER).html(json.count + " " + FLAG_PLURAL);
				}			
				FLAG_COUNTER = null;
			}
		}
		
		function removeComment(commentId) { 
			if (confirm('Are you sure you want to permanently delete this comment?')) { 
				var command = API + "?method=removeComment&comment=" + escape(commentId);
				$('#comment' + commentId).hide();
				$.getJSON(command,removeCommentSuccess);
			}		
			return false;
	
		}
		function removeCommentSuccess(comment) { 
				if (comment.error) {
					$('#comment' + comment.id).show();
					alert(comment.error);
				} else {
					$('#comment' + comment.id).hide();
				}
		}

		function removeSub(subId) { 
			var command = API + "?method=removeSub&subscriptionId=" + escape(subId);
			$('#sub_' + subId).hide();
			$.getJSON(command,removeSubSuccess);
			return false;
	
		}
		function removeSubSuccess(res) { 
				if (res.error) {
					$('#sub_' + res.id).show();
					alert(res.error);
				} else {
					$('#sub_' + res.id).hide();
				}
		}


		function approveOutlet(id) { 
		
			var command = "/outlets/edit?id=" + id + "&action=approve";
			$.getJSON(command,approveOutletSuccess);
			return false;
		
		}


		function approveOutletSuccess(json) { 
			if (json.error) {
				alert(json.error);
			} else {
				$('#outlet_'+json.id).html('&radic; Approved!');
			}
		
		}
		
		function viewRevision(id,r) { 
		
			var command = "/outlets/edit?id=" + id + "&action=revision&revision="+r;
			$.getJSON(command,viewRevisionSuccess);
			return false;
		}
		
		function viewRevisionSuccess(json) { 
		
			if (json.error) {
				alert(json.error);
			} else {
			
				if ($('#content_popup').length==0) { 
					var element = document.createElement("div");
					element.innerHTML = 'Loading!';
					element.id='content_popup';
					document.body.insertBefore(element,document.body.firstChild);
				}

				$('#content_popup').css('top',(getScrollHeight()+50)+"px");
				$('#content_popup').css('left',"50px");

				$('#content_popup').html(json.html);
				$('#content_popup').show();
				
			}
		
		}
		
		
		function getScrollHeight() {
		   var h = window.pageYOffset ||
           document.body.scrollTop ||
           document.documentElement.scrollTop;
		   return h ? h : 0;
		}
		
		function closeContentPopup() { 
			$('#content_popup').hide();
			return false;
		}
		
		function toggleCorrections(obj) { 
		
				if ($(obj).parent().parent().parent().children('.policy').is(":visible")) { 
					$(obj).parent().parent().parent().children('.policy').hide();
					$(obj).html('&#x25BA; View corrections policies and procedures');
					$(obj).parent().parent().removeClass('activeTab');
				} else {		
					$(obj).parent().parent().parent().children('.policy').show();
					$(obj).html('&#x25BD; Hide corrections policies');
					$(obj).parent().parent().addClass('activeTab');

				}
			return false;
		}



		function changeBugStatus(bugId) {
		
			tinyMCE.triggerSave();

			var new_status = $('input:radio[name=new_bug_status]:checked').val();
			var media_outlet_contacted = $('input:radio[name=meta_media_outlet_contacted]:checked').val();
			var media_outlet_response = $('#meta_media_outlet_response').val();
			var media_outlet_responded = $('input:radio[name=meta_media_outlet_responded]:checked').val();
			var send_survey = $('#sendSurveyEmail').is(':checked');
			$.getJSON(
				'/api?method=updateBugStatus&bug=' + encodeURIComponent(bugId) + "&bug_status="+encodeURIComponent(new_status)+"&response="+encodeURIComponent(media_outlet_response)+"&contacted="+encodeURIComponent(media_outlet_contacted) + "&responded="+encodeURIComponent(media_outlet_responded)+"&survey="+encodeURIComponent(send_survey),
				function(json) { 
					if (json.status=='OK') {
						if (json.open) {
							$('#status_open').show();
							$('#status_closed').hide();
							$('#bug_status_open').attr('checked',true);
							$('#bug_status_open').val(json.bug_status);
							$('#bug_status_open_img').attr('src',json.icon_20);
							$('#bug_status_open_label').html(json.display_status);
							$('#comment_form').show();
						} else {
							$('#status_closed').show();
							$('#status_open').hide();
							$('#bug_status_closed').val(json.bug_status);
							$('#bug_status_closed').attr('checked',true);
							$('#bug_status_closed_img').attr('src',json.icon_20);
							$('#bug_status_closed_label').html(json.display_status);
							$('#comment_form').hide();
							if (!json.surveyed) { 
								window.location=siteRoot+'/bugs/edit?id='+json.bug;			
							}
						}
						$('#bug_status_icon').attr('src',json.icon_50);
						if (json.media_outlet_contacted=='yes') { 
							$('#media_outlet_contacted_yes').show();
							$('#media_outlet_contacted_no').hide();

						} else {
							$('#media_outlet_contacted_yes').hide();
							$('#media_outlet_contacted_no').show();

						}
						if (json.media_outlet_response) { 
							$('#did_media_outlet_respond').show();
						} else {	
							$('#did_media_outlet_respond').hide();
						}

						$('#media_outlet_response').html(json.media_outlet_response);

					} else {
						alert(json.error);
					}	
					hideStatusChange();				
				}
			)
			
			return false;
		}
		
		function hideStatusChange() { 
			$('#change_bug_status').hide();
			
			if ($('#bug_status_link').html()) { 
			$('#bug_status_link').bt({positions:['bottom','right'],
			  fill: 'rgba(51, 204, 0, .8)',
			   shadow: true,
			    shadowOffsetX: 3,
			    shadowOffsetY: 3,
			    shadowBlur: 8,
			    shadowColor: 'rgba(0,0,0,.6)',
			    shadowOverlap: false,	
			    padding:10,
				 cssStyles: {color: '#FFF', fontWeight: 'bold'},
			    noShadowOpts: {strokeStyle: '#999', strokeWidth: 2}});
			setTimeout("$('#bug_status_link').btOn();",500);
			}
	
			return false;
		
		}
				
		
		function showStatusChange() { 

			$('#change_bug_status').show();
			return false;
		
		}

		function chcontact() { 
			if ($('#contacted_yes').attr('checked')) { 
				$('#media_outlet_responded').show();
			} else {
				$('#media_outlet_responded').hide();
				$('#responded_no').attr('checked',true);
			}
			chresponded();

			return false;
		}

		
		function chresponded() { 
			if ($('#responded_yes').attr('checked')) { 
				$('#media_response').show();
			} else {
				$('#media_response').hide();
			}
			return false;
		}



	function mo_outletupdate(json) {
	
		if (json.id) {
			$('#media_outlet_id').val(json.id);
		//	$('#media_outlet_new').hide();
		} else {
			$('#media_outlet_id').val('');
		//	$('#media_outlet_new').show();		
		}
	
	}
	function mo_newcheck() { 
		var val = $('#media_outlet_q').val();
		$.getJSON('/api?method=bugtargetcheck&outlet='+escape(val),mo_outletupdate);
	}

