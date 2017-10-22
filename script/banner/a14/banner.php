<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div style="margin-top:-22px;">
<LINK  href="script/banner/a14/style/style.css" type=text/css rel=stylesheet>

<div id="img_heightnum" style="display:none; width:0px; height:0px;"><?php echo $img_height;?></div>
<div id="img_widthnum" style="display:none; width:0px; height:0px;"><?php echo $img_width;?></div>
<SCRIPT type=text/javascript>
var img_heightnum;
img_heightnum=0;

img_heightnum=document.getElementById('img_heightnum').innerHTML*1;


var img_widthnum;
img_widthnum=0;

img_widthnum=document.getElementById('img_widthnum').innerHTML*1;
 

(function($) {

	$.fn.easySlider = function(options){
	  
		// default configuration properties
		var defaults = {			
			prevId: 		'prevBtn',
			prevText: 		'Previous',
			nextId: 		'nextBtn',	
			nextText: 		'Next',
			controlsShow:	true,
			controlsBefore:	'',
			controlsAfter:	'',	
			controlsFade:	true,
			firstId: 		'firstBtn',
			firstText: 		'First',
			firstShow:		false,
			lastId: 		'lastBtn',	
			lastText: 		'Last',
			lastShow:		false,				
			vertical:		false,
			speed: 			<?php echo $play_speed?$play_speed:5000;?>,
			auto:			false,
			pause:			3600,
			continuous:		false
		}; 
		
		var options = $.extend(defaults, options);  
				
		this.each(function() {  
			var obj = $(this); 				
			var s = $("li", obj).length;
			var w = img_widthnum;
			var h = img_heightnum;
			//var w = $("li", obj).width(); 
			//var h = $("li", obj).height(); 
			obj.width(w); 
			obj.height(h); 
			obj.css("overflow","hidden");
			var ts = s-1;
			var t = 0;
			$("ul", obj).css('width',s*w);			
			if(!options.vertical) $("li", obj).css('float','left');
			
			if(options.controlsShow){
				var html = options.controlsBefore;
				if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
				html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
				html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
				if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';
				html += options.controlsAfter;						
				$(obj).after(html);										
			};
	
			$("a","#"+options.nextId).click(function(){		
				animate("next",true);
			});
			$("a","#"+options.prevId).click(function(){		
				animate("prev",true);				
			});	
			$("a","#"+options.firstId).click(function(){		
				animate("first",true);
			});				
			$("a","#"+options.lastId).click(function(){		
				animate("last",true);				
			});		
			
			function animate(dir,clicked){
				var ot = t;				
				switch(dir){
					case "next":
						t = (ot>=ts) ? (options.continuous ? 0 : ts) : t+1;						
						break; 
					case "prev":
						t = (t<=0) ? (options.continuous ? ts : 0) : t-1;
						break; 
					case "first":
						t = 0;
						break; 
					case "last":
						t = ts;
						break; 
					default:
						break; 
				};	
				
				var diff = Math.abs(ot-t);
				var speed = diff*options.speed;						
				if(!options.vertical) {
					p = (t*w*-1);
					$("ul",obj).animate(
						{ marginLeft: p }, 
						speed
					);				
				} else {
					p = (t*h*-1);
					$("ul",obj).animate(
						{ marginTop: p }, 
						speed
					);					
				};
				
				if(!options.continuous && options.controlsFade){					
					if(t==ts){
						$("a","#"+options.nextId).hide();
						$("a","#"+options.lastId).hide();
					} else {
						$("a","#"+options.nextId).show();
						$("a","#"+options.lastId).show();					
					};
					if(t==0){
						$("a","#"+options.prevId).hide();
						$("a","#"+options.firstId).hide();
					} else {
						$("a","#"+options.prevId).show();
						$("a","#"+options.firstId).show();
					};					
				};				
				
				if(clicked) clearTimeout(timeout);
				if(options.auto && dir=="next" && !clicked){;
					timeout = setTimeout(function(){
						animate("next",false);
					},diff*options.speed+options.pause);
				};
				
			};
			// init
			var timeout;
			if(options.auto){;
				timeout = setTimeout(function(){
					animate("next",false);
				},options.pause);
			};		
		
			if(!options.continuous && options.controlsFade){					
				$("a","#"+options.prevId).hide();
				$("a","#"+options.firstId).hide();				
			};				
			
		});
	  
	};

})(jQuery);

</SCRIPT>
<style type="text/css">
#content_cndns14 {
	MARGIN: 0px auto; WIDTH: <?php echo $img_width;?>px; BACKGROUND-COLOR: #ffffff
}
#feature_cndns14 {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px auto; WIDTH: <?php echo $img_width;?>px; PADDING-TOP: 0px; POSITION: relative; HEIGHT: <?php echo $img_height;?>px
}
#feature_cndns14 IMG.featured {
	LEFT: <?php echo $img_width;?>px; POSITION: absolute; TOP: 0px
}
#slider_cndns14 LI {
	OVERFLOW: hidden; WIDTH: <?php echo $img_width;?>px; HEIGHT: <?php echo $img_height;?>px
}
#prevBtn {
	DISPLAY: block; LEFT: <?php echo $img_width-100;?>px; WIDTH: 30px; POSITION: absolute; TOP: <?php echo $img_height-30;?>px; HEIGHT: 28px
}
#nextBtn {
	DISPLAY: block; LEFT: <?php echo $img_width-100;?>px; WIDTH: 30px; POSITION: absolute; TOP: <?php echo $img_height-30;?>px; HEIGHT: 28px
}
#nextBtn {
	LEFT: <?php echo $img_width-50;?>px
}
</style>
<SCRIPT type=text/javascript>
		$(document).ready(function(){	
			$("#slider_cndns14").easySlider({
				auto: true,
				continuous: true 
			});
		});	
	</SCRIPT>

	<div id="content_cndns14">
			<div id="feature_cndns14">
			  <div id="slider_cndns14">
					<ul>				
				
                          <?php
		$kkk=1;
		foreach($img_order as $k=>$v){
			$urlhttp="";
			if($linkaddr[$k]&&$islink[$k]=='yes'){$urlhttp=$linkaddr[$k];}else{$urlhttp="#";}
			if($urlhttp=='http://'){$urlhttp='';}
		?>
        <li><a href="<?php echo $urlhttp;?>" <?php if (isset($img_open_type[$k+1][0])&&$img_open_type[$k+1][0]=='1'){ ?> target="_self" <?php }else{ ?> target="_blank"  <?php } ?>><img src="<?php echo $img_src[$k]; ?>" alt="<?php echo $sp_title[$k];?>"  height="<?php echo $img_height;?>"   width="<?php echo $img_width;?>"/></a></li>
    <?php
		$kkk++;
		}
		?>
					</ul>
				</div>
			</div>
</div>        
</div>