<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel=stylesheet type=text/css href="script/banner/a15/css/style.css">
<style>
	.jcImgScroll li a { height:<?php echo intval($img_height);?>px;  }
</style>
<div id="a15_slider" class="jcImgScroll" style="margin:0 auto;WIDTH: <?php echo $img_width;?>px; height:<?php echo intval($img_height);?>px;overflow:hidden;">
            <ul>
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
                <li style="text-align: left;">
				<a href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?> path="<?php echo $img_src[$k]; ?>" title="<?php echo $sp_title[$k];?>"></a>
			</li>
			<?php }  ?>
            </ul>
</div>
<script src="script/banner/a15/js/jQuery-easing.js" language="javascript" type="text/javascript"></script>
<script src="script/banner/a15/js/jQuery-jcImgScroll.js" language="javascript" type="text/javascript"></script>
<script>
	$("#a15_slider").jcImgScroll({
		arrow : {
			width:50,	
			height:<?php echo intval($img_height);?>,
			x:0,
			y:0
		},
	    width:<?php echo intval(floatval($img_width)*2/3);?>,
		count :<?php echo count($img_order)>5?5:(count($img_order)%2==0?count($img_order)-1:count($img_order)); ?>,
	    height:<?php echo intval($img_height);?>,
		autoplay:<?php echo $play_speed?$play_speed:5000;?>
	});
</script>