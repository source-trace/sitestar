<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
if (sizeof($bulletins_list)) {
?>
<style type="text/css">

.box { height:125px;line-height:25px;  overflow:hidden;} 
.box h4{font-weight:normal;} 
.box ul{margin:0; padding:0} 
.box li{height:25px; line-height:25px; font-size:12px; text-align:center; list-style-type:none;} 
.bulletin h4 {font-weight:normal; font-size:12px;}
</style>

<div class="list_con notice_con" id="andyscroll<?php echo $randstr;?>">
	<div class="bulletin box"  id="scrollmessage<?php echo $randstr;?>">
	<?php 
		foreach($bulletins_list as $k=>$bulletin) {
			echo '<h4><a href="'.Html::uriquery2('mod_bulletin', 'bulletin_content', array('bulletin_id' => $bulletin->id)).'">'.$bulletin->title.'</a></h4>';
		}
	?>
	</div>
	<div id="tab2"></div>
</div><div class="list_bot"></div>
<?php
} else {
	echo '<div class="list_main"><div class="marquee bulletin" style="margin-top:15px;">'.__('No Records!').'</div><div class="list_bot"></div></div><div class="blankbar"></div>';
}
if($bulletin_type=='1'){
	if(count($bulletins_list)>4 ){
?>
<script type="text/javascript"> 
function startmarquee(lh,speed,delay,index){ 
	var t; 
	var p=false; 
	var o=document.getElementById("scrollmessage<?php echo $randstr;?>"); 
	
	o.innerHTML+=o.innerHTML; 
	o.onmouseover=function(){p=true} 
	o.onmouseout=function(){p=false} 
	o.scrollTop = 0; 
	function start(){ 
		t=setInterval(scrolling,speed); 
		if(!p){ o.scrollTop += 1;} 
	} 
	function scrolling(){ 
		if(o.scrollTop%lh!=0){ 
			o.scrollTop += 1; 
			if(o.scrollTop>=o.scrollHeight/2) o.scrollTop = 0; 
		}else{ 
			clearInterval(t); 
			setTimeout(start,delay); 
		} 
	} 
	setTimeout(start,delay); 
} 
startmarquee(20,<?php echo $bulletin_speed?$bulletin_speed:40; ?>,0,1); 
</script>
<?php 
}
} 
?>