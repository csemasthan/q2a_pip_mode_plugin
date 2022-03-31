<?php

class qa_html_theme_layer extends qa_html_theme_base {

			

// code to add Button in the question

	public function q_view_buttons($q_view)
	{
		if($this -> template == 'question')
		{
		if(qa_is_logged_in())
		{
			$q_view['form']['buttons']['q_pip'] = array("tags" => 'data-postid="'.$q_view['raw']['postid'].'"  id="q_pip"', "label" => "PIP BOX", "popup" => "Show question in PIP MODE");

		}
		}
		qa_html_theme_base::q_view_buttons($q_view);

	}
	
	
	function head_script()
	{
		qa_html_theme_base::head_script();
		$enabled_plugins = qa_opt('enabled_plugins');
				
		if(qa_is_logged_in() ) {
		
			if($this->template === 'question')
		{
		
			//$this->output($enabled_plugins);

			$this->output('
<script type="text/javascript">
$(document).ready(function()
{
	// prevent submit
	$("#q_pip").attr("type", "button"); 
	
	var QUESTION_CONTENT = document.getElementsByClassName("qa-q-view-content")[0]; 
	var i=0;
	$("#q_pip").click( function Create_PIPBOX(){  
	
		var QUESTION_PIPBOX = document.createElement("div");
		if(i==0){
			alert("Question will be open in PIP mode once it is out of visibility ");
			i=1;
		}
    		QUESTION_PIPBOX.setAttribute(\'id\',\'PIPBOX\');
    		QUESTION_PIPBOX.innerHTML = "<div id=\"PIPBOX_Header\"></div><div id=\"PIPBOX_Content\"></div>";
    		document.body.append(QUESTION_PIPBOX);
    		document.getElementById("PIPBOX_Header").innerHTML="PIP MODE of Question<span id=\'close\' style=\"float:right;\">X</span>";
		document.getElementById("PIPBOX_Content").innerHTML=QUESTION_CONTENT.innerHTML;
		document.getElementById("close").onclick=function Close_PIPBOX(){document.getElementById("PIPBOX").remove();};
		
		dragElement(document.getElementById("PIPBOX"));
/*
	1. creating Main division
	2. setting id for the Main Division, for further reference
	3. Creating two divisions in the Main division, 
	4. Display the main division on the page
	5. Content of the Division_Header
	6. Content of the Division_Content
	7. Script for Deleting the Main division from the page.
	8. Making the Main division movable.
*/

	}
);

// Well known Draggable function for an element script adding
function dragElement(elmnt){
	var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
	if (document.getElementById("PIPBOX_Header")) {
			document.getElementById("PIPBOX_Header").onmousedown = dragMouseDown;
	} else {
			elmnt.onmousedown = dragMouseDown;
	}

	function dragMouseDown(e) {
			e = e || window.event;
			e.preventDefault();
			// get the mouse cursor position at startup:
			pos3 = e.clientX;
			pos4 = e.clientY;
			document.onmouseup = closeDragElement;
			// call a function whenever the cursor moves:
			document.onmousemove = elementDrag;
	}

	function elementDrag(e) {
			e = e || window.event;
			e.preventDefault();
			// calculate the new cursor position:
			pos1 = pos3 - e.clientX;
			pos2 = pos4 - e.clientY;
			pos3 = e.clientX;
			pos4 = e.clientY;
			// set the elements new position:
			elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
			elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	}

	function closeDragElement() {
			// stop moving when mouse button is released:
			document.onmouseup = null;
			document.onmousemove = null;
	}
}


// Well known VISIBLE of an element script adding
function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)

    );
}


const question_visible = document.querySelector(".qa-q-view-content");

// Attaching the VISIBLE script to Event scroll
document.addEventListener("scroll", function () {
    isInViewport(question_visible) ? 
	(document.getElementById("PIPBOX") === null ? "": document.getElementById("PIPBOX").style.visibility="hidden") 
	: (document.getElementById("PIPBOX") === null ? "": (document.getElementById("PIPBOX").style.visibility="visible",document.getElementById("PIPBOX_Content").innerHTML=QUESTION_CONTENT.innerHTML));
	

}, {
    passive: true
});
});


</script>
<style type="text/css">
#PIPBOX{
top:2px;
right:2px;
z-index:9999;
border:1px solid LightGray;
width:30%;
height:auto;
border-radius:5px;
overflow:auto;
resize:both;
position:fixed;
visibility:hidden;
	/* initially making hidden, after that it will be Visible depends upon question visibility */
}

#PIPBOX_Header{
background-color: LightGray;
border-bottom: 1px solid white;
text-align: center;
cursor: move;
line-height:3;
}

#PIPBOX_Content{
background-color: LightGray;
text-indent: 10px;
padding:5px;
}

#close{
top:0px;
text-align: right;
right:25px;
position:relative;
cursor:pointer;
}
</style>
					');
		}
		}
	}
	 // end head_script

}
