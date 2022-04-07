<?php

class qa_html_theme_layer extends qa_html_theme_base {

	public  $question_content,$i=0;
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
    		PIPBOX.innerHTML = "<div id=\"PIPBOX_Header\"></div><div id=\"PIPBOX_Content\"></div>";
    		document.body.append(PIPBOX);
		dragElement(document.getElementById("PIPBOX"));
	}

	document.getElementById("PIPBOX_Header").innerHTML="PIP MODE of "+Header+"<span id=\'close\' style=\"float:right;\">X</span>";
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
top:40px;
right:5px;
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
