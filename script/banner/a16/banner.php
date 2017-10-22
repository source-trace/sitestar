<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel=stylesheet type=text/css href="script/banner/a16/css/lrtk.css">
<script type=text/javascript src="script/banner/a16/js/slider.js"></script>
<style>
	#a16_slider .slide {WIDTH: <?php echo $img_width;?>px;  HEIGHT: <?php echo intval($img_height);?>px; }
</style>
<div id="a16_slider" style="margin:0 auto;WIDTH: <?php echo $img_width;?>px; height:<?php echo intval($img_height);?>px;">
	<?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$classname='';
			if($kkk==1){$classname='bottom:0';}
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){if($urlhttp=='http://'){$urlhttp='#';}}
			if(empty($urlhttp) || $urlhttp=='#'){
				$img_open_type[$k+1][0]='1';
				$urlhttp='#';
			}
		?>
	<div class=slide>
		<a href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>>
			<img class="diapo" border=0 width="<?php echo intval(floatval($img_width)*0.84);?>" height="<?php echo $img_height;?>" src="<?php echo $img_src[$k]; ?>">
		</a> 
	<div class=text><?php echo $sp_title[$k];?></div>
	</div>
	<?php }  ?>
</div>

<script type=text/javascript>
slider.init(<?php echo $play_speed?$play_speed:5000;?>);
</script>