<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div style="margin-top:-22px;">
<STYLE type=text/css>
#rotator_cndns12 {
	WIDTH: <?php echo $img_width;?>px; HEIGHT: <?php echo $img_height;?>px;
	margin:0 auto;
	margin-top:0px;
	padding-top:-10px;
	background-color:#transparent;
	
	}
</STYLE>
<?php
		$kkk=1;
		$pushName='';
		$pushSrc='';
		$pushLink='';
		$pushLinkType='';
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$fname=',';
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($kkk==1){$fname='';}
	
			if(!$urlhttp){
				$urlhttp="#";
			}
			if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ 
				$tag_target = "_self"; 
			}else{ 
				$tag_target = "_blank";
			} 

			if($urlhttp=='http://'){$urlhttp='';}
			$pushLink.=$fname."{src: '".$img_src[$k]."', href: '".$urlhttp."'}";
			$pushLinkType .= $fname.'"'.$tag_target.'"';//打开窗口
		
		$kkk++;
		}
?><div id="img_heightnum" style="display:none; width:0px; height:0px;"><?php echo $img_height;?></div><div id="img_widthnum" style="display:none; width:0px; height:0px;"><?php echo $img_width;?></div><div style="margin:0 auto;margin-top:0px;padding-top:0px; text-align:center; width:<?php echo $img_width;?>px;HEIGHT: <?php echo $img_height;?>px; background-color:#transparent">
<DIV id=rotator_cndns12>
	<SCRIPT type=text/javascript>
	var pushLinkType =[<?php echo $pushLinkType; ?>]; //打开窗口
		jQuery(function($) {$(document).ready(function() {
			$('#rotator_cndns12').crossSlide(
				{sleep: <?php echo $play_speed?$play_speed:5000;?>, fade: 1, debug: true},
				[
	<?php echo $pushLink;?>
				]
			);
		});});
	</SCRIPT>
</DIV>
</div>
<SCRIPT src="script/banner/a13/js/jquery.cross-slide.js" type=text/javascript></SCRIPT>
</div>
