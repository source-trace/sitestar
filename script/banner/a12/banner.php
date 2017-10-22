<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" rev="stylesheet" href="script/banner/a12/css/css.css" type="text/css" media="all" />
<?php

function utf8Substrcndns12($str,$len)
{
for($i=0;$i<$len;$i++)
{
$temp_str=substr($str,0,1);
if(ord($temp_str) > 127)
{
$i++;
if($i<$len)
{
$new_str[]=substr($str,0,3);
$str=substr($str,3);
}
}
else
{
$new_str[]=substr($str,0,1);
$str=substr($str,1);
}
}
return join($new_str);
}





?>
<div style="margin:0 auto; width:<?php echo $img_width;?>px; text-align:center; height:<?php echo $img_height+43;?>px;">
<DIV class="absolute overflow" id=push style="LEFT: 203px; WIDTH: <?php echo $img_width;?>px; TOP: 0px">
<DIV id=mainPush></DIV>
<DIV id=linkPush style="BACKGROUND: url(script/banner/a12/images/pushBg.gif); HEIGHT: 43px"></DIV></DIV>
</div>
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
			$str=utf8Substrcndns12($sp_title[$k],13);
			$pushName.=$fname.'"'.$str.'"';
			$pushSrc.=$fname.'"'.$img_src[$k].'"';
			if(!$urlhttp){
				$urlhttp="#";
			}
			if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ 
				$tag_target = "_self"; 
			}else{ 
				$tag_target = "_blank";
			}
			if($urlhttp == 'http://')
			$pushLink.=$fname.'""';
			else
			$pushLink.=$fname.'"'.$urlhttp.'"';
			$pushLinkType .= $fname.'"'.$tag_target.'"';
		
		$kkk++;
		}
		?>
<SCRIPT type=text/javascript>
function getidname(x){
    return document.getElementById(x);
}
rnd.today=new Date(); 
rnd.seed=rnd.today.getTime(); 
function rnd(){ 
	rnd.seed = (rnd.seed*9301+49297) % 233280; 
	return rnd.seed/(233280.0); 
}
function rand(number){ 
	return Math.ceil(rnd()*number)-1; 
}
//标题 .title
var pushName=[<?php echo $pushName;?>];
//图片 .image
var pushSrc=[<?php echo $pushSrc;?>];
//链接 .link
var pushLink=[<?php echo $pushLink;?>]
var pushLinkType =[<?php echo $pushLinkType; ?>]
var pushShow="";
function showPushLink(num){
	if(!num&&num!=0){
		mainPushNum++;
		if(mainPushNum><?php echo $img_ordernum-1;?>) mainPushNum=0;
		num=mainPushNum;
	}
	for(i=0;i<<?php echo $img_ordernum;?>;i++){
		getidname("linkPush"+i).className="";
		getidname("linkPush"+i).innerHTML="<img src='script/banner/a12/images/push"+i+".gif'>";
	}
	getidname("linkPush"+num).className="linkPushHere";
	getidname("linkPush"+num).innerHTML="<strong class='fontOrange'>"+(num+1)+".</strong>"+pushName[num];
	getidname("pushImg").src=pushSrc[num];
	getidname("pushImgLink").href=pushLink[num];
	getidname("pushImg").alt=pushName[num];
}
//初始化
for(i=0;i<<?php echo $img_ordernum;?>;i++){
	pushShow+='<a href="'+pushLink[i]+'" onmouseover="showPushLink('+i+');clearInterval(rollId)" id="linkPush'+i+'" target="'+pushLinkType[i]+'"><img src="script/banner/a12/images/push'+i+'.gif"></a>';
}
getidname("linkPush").innerHTML=pushShow;
var mainPushNum=rand(<?php echo $img_ordernum;?>);
getidname("linkPush"+mainPushNum).className="linkPushHere";
getidname("linkPush"+mainPushNum).innerHTML="<strong class='fontOrange'>"+(mainPushNum+1)+".</strong>"+pushName[mainPushNum];
getidname("mainPush").innerHTML='<a href="'+pushLink[mainPushNum]+'" target="'+pushLinkType[mainPushNum]+'" id="pushImgLink" onmouseover="clearInterval(rollId)" onmouseout="showAtTime()"><img src="'+pushSrc[mainPushNum]+'" name="pushImg" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>" id="pushImg" alt="'+pushName[mainPushNum]+'" /></a>';
var rollId=setInterval("showPushLink()",<?php echo $play_speed?$play_speed:5000;?>);
function showAtTime(){
	showPushLink();
	rollId=setInterval("showPushLink()",<?php echo $play_speed?$play_speed:5000;?>);
}
</SCRIPT>