<?php

class qa_html_theme_layer extends qa_html_theme_base {

	public  $question_content;
	public $answer_content=array();	
	public  $load_pip_by_default=0;
	
	function doctype()
	{
		if($this->template == 'account'){
			$pipcheckbox_form = $this->pip_form_generate();			
			if($pipcheckbox_form){
				$this->content['form_pipcheckbox'] = $pipcheckbox_form;
			}
		}
		qa_html_theme_base::doctype();
	}
	
	function pip_form_generate(){
		if($handle = qa_get_logged_in_handle()) {
			require_once QA_INCLUDE_DIR . 'db/metas.php';
			$userid = qa_get_logged_in_userid();
			if (qa_clicked('pipsettings_save')) {
			$field_value = empty(qa_post_text('pip_check_box'))?"0":"1";
			qa_db_usermeta_set($userid, 'PIP', $field_value ) ;
			qa_redirect($this->request,array('ok'=>qa_lang_html('admin/options_saved')));
			}
		}
		$ok = qa_get('ok')?qa_get('ok'):null;
		$fields = array();
		$fields['pip_check_box'] = array(
				'label' => "Enable PIP mode of Question Automatically",
				'tags' => 'NAME="pip_check_box"',
				'type' => 'checkbox',
				'value' => qa_db_usermeta_get($userid, 'PIP'),
				);
		
			$form=array(

					'ok' => ($ok && !isset($error)) ? $ok : null,

					'style' => 'tall',

					'title' => "PIP BOX DEFAULT OPTION",

					'tags' =>  'action="" method="POST"',

					'fields' => $fields,
					
					'label' => "PIP CHECK BOX FORM",

					'buttons' => array(
						array(
							'label' => qa_lang_html('main/save_button'),
							'tags' => 'NAME="pipsettings_save"',
						     ),
						),
				   );
			return $form;	
	}	

	public function a_item_buttons($a_item)
	{
		if($this -> template == 'question')
		{
		if(qa_is_logged_in())
		{
			$answer_content=$a_item['content'];
			
			$answer_id=explode('"',$answer_content)[1];
			$a_item['form']['buttons']['a_pip'] = array("tags" => 'id="APIP_'.$answer_id.'"', "label" => "PIP BOX", "popup" => "Show answer in PIP MODE");

			$this->output('
<script type="text/javascript">

$(document).ready(function()
{
var answer_number_on_the_page=get_answer_number();
$("#APIP_'.$answer_id.'").attr("type", "button"); 

$("#APIP_'.$answer_id.'").click( function clicked_Answer(){

CONTENT=document.querySelectorAll(".qa-a-item-content")[answer_number_on_the_page].innerHTML;
Header="ANSWER";
visible_item = document.querySelectorAll(".qa-a-item-content")[answer_number_on_the_page];
Create_PIPBOX(CONTENT,Header);

});
});
</script>');	
		}		
		}
		qa_html_theme_base::a_item_buttons($a_item);

	}
	
	
	function head_script()
	{
		qa_html_theme_base::head_script();
		if($this -> template == 'question')
		{
		if(qa_is_logged_in())
		{
		$this->output('
<script>
	var visible_item = document.querySelector(".qa-q-view-content");
	var CONTENT;
	var Header;
	var answer_number=0;
function get_answer_number(){
	return answer_number++;
}
function Clicked_Question(){
	if(i==0){
		alert("Question will be open in PIP mode once it is out of visibility ");
		i=1;
	}
	visible_item=document.querySelector(".qa-q-view-content");
	CONTENT=document.getElementsByClassName("qa-q-view-content")[0].innerHTML;
	Header="QUESTION";
	Create_PIPBOX(CONTENT,Header);
}
	
function Create_PIPBOX($content,$header){  
	CONTENT = $content;
	Header = $header;  
	if(!document.getElementById("PIPBOX"))
	{
		var PIPBOX = document.createElement("div");
    		PIPBOX.setAttribute("id","PIPBOX");
    		PIPBOX.innerHTML = "<div id=\\"PIPBOX_Header\\"></div><div id=\\"PIPBOX_Content\\"></div>";
    		document.body.append(PIPBOX);
		dragElement(document.getElementById("PIPBOX"));
	}

	document.getElementById("PIPBOX_Header").innerHTML="PIP MODE of "+Header+"<span id=\'close\' style=\\"float:right;\\">X</span>";
	document.getElementById("PIPBOX_Content").innerHTML=CONTENT;
	document.getElementById("close").onclick=function (){Close_PIPBOX();};
		
	if(document.getElementById("PIPBOX"))
	{
		document.getElementById("PIPBOX").style.width="30%";
		document.getElementById("PIPBOX").style.height="auto";

	}
}

function Close_PIPBOX(){
	document.getElementById("PIPBOX_Content").remove();
	document.getElementById("PIPBOX").remove();
};

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
	if(!el)
		return ;
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)

    );
}



$(document).ready(function()
{

	// prevent submit
	$("#q_pip").attr("type", "button"); 
	i=0;
	$("#q_pip").click( function Click(){Clicked_Question();}	);

// Attaching the VISIBLE script to Event scroll
document.addEventListener("scroll", function () {
    if(isInViewport(visible_item))
    {
		if(document.getElementById("PIPBOX") === null)
		{}
		else{
		document.getElementById("PIPBOX").style.visibility="hidden";
		}
	}
	else{
		if(document.getElementById("PIPBOX") === null)
		{
			//alert("empty");
		}
		else{
			
			document.getElementById("PIPBOX").style.visibility="visible";
			document.getElementById("PIPBOX_Content").innerHTML=CONTENT;
		}

}}, {
    passive: true
});
});


</script>
			
			');
			
		$this->output('
<style>

#PIPBOX{
	top:45px;
	right:5px;
	z-index:9999;
	border:1px solid #dfe6ee;
	width:30%;
	height:auto;
	border-radius:10px;
	overflow:hidden;
	resize:both;
	position:fixed;
	visibility:hidden;
	background:#f8fafc;
	color:#111827;
	box-shadow:0 14px 32px rgba(15, 23, 42, 0.12);
	font-family:Arial, sans-serif;
	/* initially making hidden, after that it will be Visible depends upon question visibility */
}

html[data-theme="dark"] #PIPBOX{
	background:#111827;
	border-color:#374151;
	color:#f3f4f6;
	box-shadow:0 16px 36px rgba(0, 0, 0, 0.35);
}

#PIPBOX_Header{
	background:linear-gradient(180deg, #edf2f7 0%, #e2e8f0 100%);
	border-bottom:1px solid #dfe6ee;
	text-align:center;
	cursor:move;
	line-height:1.4;
	padding:10px 12px;
	font-weight:700;
	font-size:13px;
	position:relative;
}

html[data-theme="dark"] #PIPBOX_Header{
	background:linear-gradient(180deg, #1f2937 0%, #17212d 100%);
	border-bottom-color:#374151;
	color:#f3f4f6;
}

#PIPBOX_Content{
	background:#ffffff;
	color:#1f2937;
	text-indent:0;
	padding:12px 14px;
	line-height:1.6;
	max-height:70vh;
	overflow:auto;
	word-break:break-word;
}

html[data-theme="dark"] #PIPBOX_Content{
	background:#0f172a;
	color:#f3f4f6;
}

#PIPBOX_Content *{
	color:inherit;
}

#close{
	top:0;
	text-align:center;
	right:0;
	position:absolute;
	cursor:pointer;
	width:22px;
	height:22px;
	border-radius:50%;
	line-height:22px;
	font-size:12px;
	font-weight:700;
	background:rgba(15, 23, 42, 0.06);
	color:#374151;
	transition:background 0.2s ease;
}

#close:hover{
	background:rgba(15, 23, 42, 0.12);
}

html[data-theme="dark"] #close{
	background:rgba(148, 163, 184, 0.12);
	color:#f3f4f6;
}

html[data-theme="dark"] #close:hover{
	background:rgba(148, 163, 184, 0.2);
}
</style>
		');	
	}
	}
	}
	 // end head_script



// code to add Button in the question

	public function q_view_buttons($q_view)
	{
		if($this -> template == 'question')
		{
		if(qa_is_logged_in())
		{
			$q_view['form']['buttons']['q_pip'] = array("tags" => 'data-postid="'.$q_view['raw']['postid'].'"  id="q_pip"', "label" => "PIP BOX", "popup" => "Show question in PIP MODE");

		}
		require_once QA_INCLUDE_DIR . 'db/metas.php';
		$userid = qa_get_logged_in_userid();
		
		settype($load_pip_by_default,'integer');
			$load_pip_by_default=qa_db_usermeta_get($userid, 'PIP');
		if($load_pip_by_default==1)
				{
			$this->output('
<script type="text/javascript">
$(document).ready(function()
{
i=1;
Clicked_Question();
});
</script>');	
		
		}
		
		
		

		
				}
				qa_html_theme_base::q_view_buttons($q_view);

	}
}
