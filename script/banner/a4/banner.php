<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<LINK media=all href="script/banner/a4/css/tpbk-activity.css" type=text/css rel=stylesheet>
<style type="text/css">
#lantern_slide_cndns4 {
	 OVERFLOW: hidden;  HEIGHT: <?php echo $img_height+44;?>px; margin:0 auto; padding:0; text-align:center; width:<?php echo $img_width;?>px;
}

#bimg_cndns4 {
	FILTER: progid:DXImageTransform.Microsoft.Fade ( duration=0.5,overlap=1.0 ); OVERFLOW: hidden; HEIGHT: <?php echo $img_height;?>px
}
.pic .dis A {
	DISPLAY: block; FONT-SIZE: <?php echo $img_height-38;?>px; VERTICAL-ALIGN: middle; OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; FONT-FAMILY: Arial; HEIGHT: <?php echo $img_height;?>px; TEXT-ALIGN: center
}
.pic .dis A IMG {
	MAX-WIDTH: <?php echo $img_width;?>px; VERTICAL-ALIGN: middle
}
</style>
<DIV id=lantern_slide_cndns4>
<TABLE class=ge id=ge cellSpacing=0 cellPadding=0>
  <TBODY>
  <TR>
    <TD class=pic id=bimg_cndns4>
     <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$classname='undis';
			if($kkk==1){$classname='dis';}
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp && $urlhttp!="http://"){
		?>

 <DIV class="<?php echo $classname;?>" name="f"><A href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>><IMG alt="<?php echo $sp_title[$k]; ?>" src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>"></A></DIV>
               <?php
			}else{
			?>
 <DIV class="<?php echo $classname;?>" name="f"><A href="#" ><IMG alt="<?php echo $sp_title[$k]; ?>" src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>"></A></DIV>
          <?php
			}
		
		$kkk++;
		}
		?>


      <TABLE id=font_hd_cndns4 cellSpacing=0 cellPadding=0>
        <TBODY>
        <TR>
          <TD class=lkff id=info_cndns4>
           <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$classname='undis';
			if($kkk==1){$classname='dis';}
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){
		?>
		
  <DIV class="<?php echo $classname;?>" name="f"><A href="<?php echo $urlhttp;?>" target=_blank><?php echo $sp_title[$k]; ?></A></DIV>
               <?php
			}else{
			?>
  <DIV class="<?php echo $classname;?>" name="f"><A href="#" ><?php echo $sp_title[$k]; ?></A></DIV>
          <?php
			}
		
		$kkk++;
		}
		?>

            
            </TD>
          <TD id=simg_cndns4>
            <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
		?>
            <DIV class="" onclick=play(x[<?php echo $kkk-1;?>],<?php echo $kkk-1;?>) name="f"><?php echo $kkk;?></DIV>
               <?php		
		$kkk++;
		}
		?>
       
		</TD></TR></TBODY></TABLE>

    </TD></TR></TBODY></TABLE></DIV>

<SCRIPT  type="text/javascript">
	function getid(o){ return (typeof o == "object")?o:document.getElementById(o);}
	function getNames(obj,name,tij)
	{
		var plist = getid(obj).getElementsByTagName(tij);
		var rlist = new Array();
		for(i=0;i<plist.length; ++i){if(plist[i].getAttribute("name") == name){rlist[rlist.length] = plist[i];}}
		return rlist;
	}

	function fiterplay(obj,num,t,name,c1,c2)
	{
		var fitlist = getNames(obj,name,t);
		for(i=0;i<fitlist.length;++i)
		{
			if(i == num)
			{
				fitlist[i].className = c1;
			}
			else
			{
				fitlist[i].className = c2;
			}
		}
	}
	function play(obj,num)
	{
		var s = getid('simg_cndns4');
		var i = getid('info_cndns4');
		var b = getid('bimg_cndns4');
		try	
		{
			with(b)
			{
				filters[0].Apply();	
				fiterplay(b,num,"div","f","dis","undis");	
				fiterplay(s,num,"div","f","","f1");
				fiterplay(i,num,"div","f","dis","undis");
				filters[0].play();
			}
		}
		catch(e)
		{
				fiterplay(b,num,"div","f","dis","undis");
				fiterplay(s,num,"div","f","","f1");	
				fiterplay(i,num,"div","f","dis","undis");
		}
	}

	var autoStart = 0;
	var n = 0;		var s = getid("simg_cndns4");
		var x = getNames(s,"f","div");
	function clearAuto() {clearInterval(autoStart);};
	function setAuto(){autoStart=setInterval("auto(n)", <?php echo $play_speed?$play_speed:5000;?>)}
	function auto()	{


		n++  ;
		if(n>(x.length-1))
		{ n = 0;
		//clearAuto();
		 }
		play(x[n],n);
		
	}
	function ppp(){
	setAuto();
	
	}
ppp();
</SCRIPT>

