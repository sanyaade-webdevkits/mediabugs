
/*
q is the bug query: defaults to open bugs
div is the div to append the results to: defaults to 'bug_list'
bug_dispay is a string that will be converted into a jquery template

widget.css styles the output via class bug_display
 */

function bugWidget(q, div, bug_display){

        if (!q){
            q= 'status?q=open';
        }

        if(!div){
            div= 'bug_list';
        }
        
        div= '#'+div;

        if(bug_display== null){

           var bug_display= '<img src="${stat_img}" alt="${bug_status}" width="50" height="50" />\n\
                             <span class="txt_blk"><a href=${permalink}>${headline}</a><br />\n\
                             From: &nbsp; ${pub_link}';
        }

        bug_display= "<div class='bug_display'>"+bug_display+"</div>";

        var t    = $.template(bug_display);
        
        $.getJSON('http://localhost/mediabugs/mediabugs-open/bugs/json/'+q, function(json){

            for (var x in json) {

                var bug = json[x];
                $(div).append(t, bug);
            }
        });
 };
 

$(document).ready(function (){

  if($('#widget').html()){

    //optional args bugWidget(query, div, bug_display)
     bugWidget();
 }

});


