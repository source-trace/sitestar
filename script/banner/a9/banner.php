<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script src="script/banner/a9/js/slides.min.jquery.js"></script>
	<script>
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'script/banner/a9/img/loading.gif',
				play: <?php echo $play_speed?$play_speed:5000;?>,
				pause: 2500,
				hoverPause: true,
				animationStart: function(){
					$('.caption').animate({
						bottom:-35
					},100);
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log(current);
					};
				}
			});
		});
	</script>
<link rel="stylesheet" href="script/banner/a9/css/global.css">
<style type="text/css">

#container {
	width:<?php echo $img_width;?>px;
	padding:0px;
	position:relative;
	z-index:0;margin:0 auto; padding:0; text-align:center;
}

#example {
	width:<?php echo $img_width;?>px;
	height:<?php echo $img_height+0;?>px;
	position:relative;
	background-color:#FFFFFF;
}
#frame {
	position:absolute;
	z-index:0;
	width:<?php echo $img_width;?>px;
	height:<?php echo $img_height+0;?>px;

}
#slides .next {
	left:<?php echo $img_width-32;?>px;

}
.slides_container {
	width:<?php echo $img_width;?>px;
	height:<?php echo $img_height+0;?>px;
	overflow:hidden;
	position:relative;
}
.caption {
	position:absolute;
	bottom:-35px;
	height:0px;
	padding:0px;
	background:#000;
	background:rgba(0,0,0,.5);
	width:<?php echo $img_width;?>px;
	font-size:1.3em;
	line-height:1.33;
	color:#fff;
	border-top:0px solid #000;
	text-shadow:none;
}
</style>
	<div id="container">
		<div id="example">
			<img src="script/banner/a9/img/new-ribbon.png" width="112" height="112" alt="New Ribbon" id="ribbon">
			<div id="slides">
				<div class="slides_container">
                
                <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			$classname='';
			if($kkk==1){$classname='bottom:0';}
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}
			if($urlhttp){if($urlhttp=='http://'){$urlhttp='';}
		?>	<div>
						<a href="<?php echo $urlhttp;?>" title="<?php echo $sp_title[$k];?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>><img src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height+0;?>" alt="<?php echo $sp_title[$k];?>"></a>
						<div class="caption" style="<?php echo $classname;?>">
							<p><?php echo $sp_title[$k];?></p>
						</div>
					</div><?php
			}else{
			?>	<div>
						<a href="#" title="<?php echo $sp_title[$k];?>" target="_blank"><img src="<?php echo $img_src[$k]; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_height+0;?>" alt="<?php echo $sp_title[$k];?>"></a>
						<div class="caption" style="<?php echo $classname;?>">
							<p><?php echo $sp_title[$k];?></p>
						</div>
					</div><?php
			}
		
		$kkk++;
		}
		?> 
				
                    
				
                    
			
				</div>
				<a href="#" class="prev"><img src="script/banner/a9/img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next"><img src="script/banner/a9/img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>			</div>
			</div>
		</div>
