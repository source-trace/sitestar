<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div style="margin-top:-22px;">
<LINK href="script/banner/a1/css/css.css" type=text/css rel=stylesheet>
<style type="text/css">
#carousel_cndns_1{ margin:0 auto; padding:0; text-align:center; width:<?php echo $img_width;?>px; background-color:#FFFFFF}
#carousel_cndns_1 .frame {WIDTH: <?php echo $img_width;?>px; POSITION: relative; HEIGHT: <?php echo $img_height;?>px; overflow:hidden; text-align:left;}
#carousel_cndns_1 .frames {WIDTH: 2920px; POSITION: relative; HEIGHT: <?php echo $img_height;?>px}
#carousel_cndns_1 .frame-2 {LEFT: 1200px; OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; POSITION: absolute; HEIGHT: <?php echo $img_height;?>px}
#carousel_cndns_1 .frame-3 {LEFT: 1460px; OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; POSITION: absolute; HEIGHT: <?php echo $img_height;?>px}
#carousel_cndns_1 .frame-4 {LEFT: 2190px; OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; POSITION: absolute; HEIGHT: <?php echo $img_height;?>px}
#carousel_cndns_1 .frame-5 {LEFT: 2920px; OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; POSITION: absolute; HEIGHT: <?php echo $img_height;?>px}
#carousel_cndns_1 .controls { padding-left:<?php echo ($img_width-100)/2;?>px; text-align:center;  CURSOR: hand; BOTTOM: 0px; PADDING-TOP: 2px;HEIGHT: 20px}
</style>
<div id="img_heightnum" style="display:none; width:0px; height:0px;"><?php echo $img_height;?></div>
<div id="img_widthnum" style="display:none; width:0px; height:0px;"><?php echo $img_width;?></div>
<div id=carousel_cndns_1>
	<div class=frame>
		<div class=frames id=stage>
        <?php
       //var_dump($img_open_type);
		$kkk=1;
		foreach($img_order as $k=>$v){
		if($kkk==1){
			$urlhttp="";
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){
				if($urlhttp=='http://'||$urlhttp==''){$urlhttp='#';}

		?>
			<a href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>>
				<div id=f<?php echo $kkk;?> style="BACKGROUND: url(<?php echo $img_src[$k]; ?>); OVERFLOW: hidden; WIDTH: 1200px; POSITION: absolute; HEIGHT:  <?php echo $img_height;?>px"></div>
			</a>
            <?php
			}else{
			?>
            <a href="#">
				<div id=f<?php echo $kkk;?> style="BACKGROUND: url(<?php echo $img_src[$k]; ?>); OVERFLOW: hidden; WIDTH: 1200px; POSITION: absolute; HEIGHT:  <?php echo $img_height;?>px"></div>
			</a>
        <?php
			}
		}
		$kkk++;
		}
		
		$kkk=1;
		foreach($img_order as $k=>$v){
			if($kkk>1){
			$urlhttp="";
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){
				if($urlhttp=='http://'||$urlhttp==''){$urlhttp='#';}
		?>
			<a href="<?php echo $urlhttp;?>"  <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>>
				<div class=frame-<?php echo $kkk;?> id=f<?php echo $kkk;?> ></div>
			</a>
               <?php
			}else{
			?>
            <a href="#" >
				<div class=frame-<?php echo $kkk;?> id=f<?php echo $kkk;?> ></div>
			</a>
          <?php
			}
			}
		$kkk++;
		}
		?>
 		<a href="#" >
				<div class=frame-<?php echo $kkk;?> id=f<?php echo $kkk;?> ></div>
			</a>
		
		</div>
        
	</div>

	<div class=controls>
		<div class="arrow l-a" onmouseover=highlightA(this); onclick=prevF(); 
		onmouseout=dehighlightA(this);></div>
         <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
		?> 
		<div class=off id=control<?php echo $kkk;?> onmouseover=hover(<?php echo $kkk;?>) onclick=press(<?php echo $kkk;?>,false) 
		onmouseout=out(<?php echo $kkk;?>)><?php echo $kkk;?></div>
    <?php
		$kkk++;
		}
		?>
	     
		<div class="arrow r-a" onmouseover=highlightA(this); onclick=nextF(); 
		onmouseout=dehighlightA(this);></div>
	</div>

	<INPUT id=numFrame type=hidden value=<?php echo $img_ordernum;?>></INPUT>
     <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){
				if($urlhttp=='http://'){$urlhttp='#';}
		?>
	<a href="<?php echo $urlhttp;?>" target="_blank"><INPUT id=images<?php echo $kkk;?> type=hidden value="<?php echo $img_src[$k]; ?>"></INPUT></a>
               <?php
			}else{
			?>
	<a href="#"  ><INPUT id=images<?php echo $kkk;?> type=hidden value="<?php echo $img_src[$k]; ?>"></INPUT></a>
    <?php
			}
		
		$kkk++;
		}
		?>

</div>
<?php include_once("script/banner/a1/js/lanrenxixi".$img_ordernum.".php");?>
</div>